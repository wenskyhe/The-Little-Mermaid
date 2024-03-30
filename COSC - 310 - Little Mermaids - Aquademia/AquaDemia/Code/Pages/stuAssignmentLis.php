<?php
include_once "config.php"; 
include_once "dbh.inc.php";

// just for the test
$UserID = 1;
$CourseID =$_GET['CourseID'];

//echo $CourseID;
//$CourseID = 2;

// $CourseID was stored when user click into the course,
// access created assigenments in this course:
$sql_1 = "SELECT AssignmentName, ReleaseDate, DueDate
          FROM CourseAssignments
          WHERE CourseID = $CourseID";

// $UserID was stored when user has logged in
// access students' info on assignments
$sql_2 = "SELECT AssignmentName, Grade, SubmissionTime
          FROM Assignments
          WHERE StudentID = '$UserID'";

// Initialize an empty array to store the organized data
$assignmentsData = array();

// Fetch data for SQL query 1
$stmt = $pdo->query($sql_1);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //echo $row['AssignmentName'];
        $assignmentName = $row['AssignmentName'];
        $dueDate = $row['DueDate'];
        
        // Check if the assignment is current or past based on the due date
        $isCurrent = strtotime($dueDate) > time();
        
        $assignmentsData[$assignmentName] = array(
            'ReleaseDate' => $row['ReleaseDate'],
            'DueDate' => $dueDate,
            'Grade' => -1, // Initialize Grade as -1
            'SubmissionTime' => null, // Initialize SubmissionTime as null
            'IsCurrent' => $isCurrent // Store whether the assignment is current or past
        );
    }

// Fetch data for SQL query 2
$stmt = $pdo->query($sql_2);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $assignmentName = $row['AssignmentName'];
    // Check if the assignment name exists in the $assignmentsData array
    if (array_key_exists($assignmentName, $assignmentsData)) {
        // Update Grade and SubmissionTime for the assignment
       $assignmentsData[$assignmentName]['Grade'] = $row['Grade'];
        $assignmentsData[$assignmentName]['SubmissionTime'] = $row['SubmissionTime'];
    }
}
//print_r($assignmentsData);

// Close the database connection
$pdo = null;

if (isset($_SESSION['assignmentName'])) {
    unset($_SESSION['assignmentName']); // Clear the session variable
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
</head>
<body>


<h1>Assignments</h1>
<?php if(empty($assignmentsData)){
        echo "The teacher did not create any assignment. ";
    }?>
<h2>Current</h2>
<ul>
    <?php foreach ($assignmentsData as $assignmentName => $assignmentDetails) {
        if ($assignmentDetails['IsCurrent']) {
            // Create a link with assignment details using GET method
            $assignmentLink = "submitAssignmentPage.php?assignmentName=" . urlencode($assignmentName) . "&dueDate=" . urlencode($assignmentDetails['DueDate']);
            echo "<li><a href='$assignmentLink'>$assignmentName</a> (Due Date: {$assignmentDetails['DueDate']}) <br> Grade: {$assignmentDetails['Grade']}, Release Date: {$assignmentDetails['ReleaseDate']}, Submission Time: {$assignmentDetails['SubmissionTime']}</li>";
        } else {
            echo "no assignment";
        }


    } ?>
</ul>

<h2>Past</h2>
<ul>
    <?php foreach ($assignmentsData as $assignmentName => $assignmentDetails) {
        if (!$assignmentDetails['IsCurrent']) {
            // Create a link with assignment details using GET method
            $assignmentLink = "submitAssignmentPage.php?assignmentName=" . urlencode($assignmentName) . "&dueDate=" . urlencode($assignmentDetails['DueDate']);
            echo "<li><a href='$assignmentLink'>$assignmentName</a> (Due Date: {$assignmentDetails['DueDate']}) <br> Grade: {$assignmentDetails['Grade']}, Release Date: {$assignmentDetails['ReleaseDate']}, Submission Time: {$assignmentDetails['SubmissionTime']}</li>";
        } 
    } ?>
</ul>
</body>
