<?php
session_start();
require_once 'dbh.inc.php';


if (!isset($_SESSION["UserID"])) {
    echo "_SESSION[UserID] is not setted";
    header("Location: ../../Code/");
} else {
    echo "_SESSION[UserID] is " . $_SESSION["UserID"];
    $UserID = $_SESSION["UserID"];
}

function fetchEnrolledCourses($pdo, $UserID)
{
    $sql = "SELECT courseID
            FROM enrollments
            WHERE UserID = :UserID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':UserID', $UserID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function fetchCourseDetails($pdo, $courseID)
{
    $sql = "SELECT courseID, courseName
            FROM courses
            WHERE courseID = :courseID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':courseID', $courseID, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
        'courseName' => $row['courseName'],
        'AverageGrade' => null // Initialize AverageGrade
    ];
}

function fetchAverageGrade($pdo, $courseID, $UserID)
{
    $sql = "SELECT S.grade AS grade, A.Weight AS weight
            FROM submissions AS S
            INNER JOIN assignments AS A ON A.assignmentID = S.assignmentID
            WHERE A.courseID = :courseID AND S.UserID = :userID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':courseID', $courseID, PDO::PARAM_INT);
    $stmt->bindParam(':userID', $UserID, PDO::PARAM_INT);
    $stmt->execute();

    $totalGrade = 0;
    $totalWeight = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Check if the keys exist in the result set before accessing them
        if (isset($row['grade'], $row['weight'])) {
            $grade = $row['grade'];
            $weight = $row['weight'];

            if ($grade >= 0) {
                $totalGrade += $grade * $weight;
                $totalWeight += $weight;
            }
        }
    }

    return $totalWeight > 0 ? round(($totalGrade / $totalWeight) * 100, 2) / 100 : 0;
}


function fetchUpcomingAssignments($pdo, $courseIDs)
{
    $upcomingAssignments = [];

    foreach ($courseIDs as $courseID) {
        $sql = "SELECT Title, DueDate, AssignmentID
                FROM Assignments
                WHERE courseID = :courseID
                AND DueDate > NOW()";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':courseID', $courseID, PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $assignmentName = $row['Title'];
            $dueDate = $row['DueDate'];
            $AssignmentID = $row['AssignmentID'];

            $upcomingAssignments[] = [
                'AssignmentID' => $AssignmentID,
                'courseID' => $courseID,
                'Title' => $assignmentName,
                'DueDate' => $dueDate
            ];
        }
    }

    return $upcomingAssignments;
}

// execute functions and prepare data for this page1
$courseIDs = fetchEnrolledCourses($pdo, $UserID);
$courseDetails = [];

foreach ($courseIDs as $courseID) {
    $courseDetails[$courseID] = fetchCourseDetails($pdo, $courseID);
    $courseDetails[$courseID]['AverageGrade'] = fetchAverageGrade($pdo, $courseID, $UserID);
}

$upcomingAssignments = fetchUpcomingAssignments($pdo, $courseIDs);

$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Main Page</title>
    <link rel="stylesheet" href="..\Assets\CSS\studentMP.css"> <!-- Link external CSS file -->
</head>

<body>
<p style="text-align: right;">
    <a href="studentView.php"><?php echo $_SESSION["Username"] ?></a><?php echo "  " ?>
    <a href="login.html">Logout</a>
</p>
    
    <h2 style='color:#deb9fb ;'>Enrolled Courses</h2>
 
    <div class="row">
        <?php //print_r($courseDetails);
        foreach ($courseDetails as $courseID => $course) : ?>
            <div class="column">
                <a href="assignmentList.php?courseID=<?php echo urlencode($courseID); ?>">
                    <img src="../Assets/Images/default.jpg" class="hover-shadow">
                    <div class="caption">
                        <?php echo $course['courseName']; ?>
                    </div>
                </a>
                <p><?php echo "Current Grade: " . $course['AverageGrade']; ?></p>

            </div>
        <?php endforeach; ?>
    </div>

    <br>
    <h2 style='color:#deb9fb ;'>Upcoming Assignments</h2>
    <ul>
        <?php foreach ($upcomingAssignments as $assignment) : ?>
            <?php $assignmentLink = "submitAssignmentPage.php?assignmentID=" . urlencode($assignment['AssignmentID']); ?>
            <li style='color: white;'><a href="<?php echo $assignmentLink; ?>"><?php echo $assignment['Title']; ?></a>  Due Date: <?php echo $assignment['DueDate']; ?></li>
        <?php endforeach; 
        $pdo = null; ?>
    </ul>

</body>

</html>
