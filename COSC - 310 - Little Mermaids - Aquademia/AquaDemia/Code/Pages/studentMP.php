<?php
// ruby

include_once 'config.php';
include_once 'dbh.inc.php';

function fetchEnrolledCourses($pdo, $UserID)
{
    $sql = "SELECT CourseID
            FROM enrollments 
            WHERE UserID = :UserID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':UserID', $UserID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function fetchCourseDetails($pdo, $courseID)
{
    $sql = "SELECT CourseID, courseName
            FROM courses
            WHERE CourseID = :CourseID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':CourseID', $courseID, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
        'courseName' => $row['courseName'],
        'AverageGrade' => null // Initialize AverageGrade
    ];
}

function fetchAverageGrade($pdo, $courseID, $UserID)
{
    $sql = "SELECT A.assignmentID, S.grade, A.Weight
            FROM submissions AS S
            INNER JOIN assignments AS A ON A.assignmentID = S.assignmentID
            WHERE A.CourseID = :courseID AND S.UserID = :userID";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':courseID', $courseID, PDO::PARAM_INT);
            $stmt->bindParam(':userID', $UserID, PDO::PARAM_INT);
            $stmt->execute();

    

    $totalGrade = 0;
    $totalWeight = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $grade = $row['A.Grade'];
        $weight = $row['C.Weight'];

        if ($grade >= 0) {
            $totalGrade += $grade * $weight;
            $totalWeight += $weight;
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
                WHERE CourseID = :CourseID
                AND DueDate > NOW()";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':CourseID', $courseID, PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $assignmentName = $row['Title'];
            $dueDate = $row['DueDate'];
            $AssignmentID = $row['AssignmentID'];

            $upcomingAssignments[] = [
                'AssignmentID' => $AssignmentID,
                'CourseID' => $courseID,
                'Title' => $assignmentName,
                'DueDate' => $dueDate
            ];
        }
    }

    return $upcomingAssignments;
}

$UserID = 1; //_GET
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
    <link rel="stylesheet" href="styles.css"> <!-- Link external CSS file -->
</head>

<body>

    <h3>Enrolled Courses</h3>
 
    <div class="row">
        <?php //print_r($courseDetails);
        foreach ($courseDetails as $courseID => $course) : ?>
            <div class="column">
                <a href="index_assignmentLis.php?CourseID=<?php echo urlencode($courseID); ?>">
                    <img src="../Assets/Images/defaultPic.jpg" class="hover-shadow">
                    <div class="caption">
                        <?php echo $course['courseName']; ?>
                        <?php echo "Current Grade: " . $course['AverageGrade']; ?>
                    </div>
                </a>
                <br>
            </div>
        <?php endforeach; ?>
    </div>

    <br>
    <br>
    <h3>Upcoming Assignments</h3>
    <ul>
        <?php foreach ($upcomingAssignments as $assignment) : ?>
            <?php $assignmentLink = "fcn_submitAssignmentPage.php?assignmentID=" . urlencode($assignment['AssignmentID']) . "&dueDate=" . urlencode($assignment['DueDate']); ?>
            <li><a href="<?php echo $assignmentLink; ?>"><?php echo $assignment['Title']; ?></a> - Due Date: <?php echo $assignment['DueDate']; ?></li>
        <?php endforeach; 
        $pdo = null; ?>
    </ul>

</body>

</html>
