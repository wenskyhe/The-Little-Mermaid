<?php
include_once 'Aquademia/AquaDemia/Code/Assets/PHP/config.php';
include_once 'Aquademia/AquaDemia/Code/Assets/PHP/dbh.inc.php';


// just for the test
$UserID = 1;
$CourseID = 2;

if (!isset($_SESSION['assignmentName'])) {
    $assignmentName =  $_GET['assignmentName'];
    $_SESSION['assignmentName'] =  $assignmentName;
}else{
    $assignmentName = $_SESSION['assignmentName'];
}

// $CourseID was stored when user click into the course,
// access created assigenments in this course:
$sql_1 = "SELECT Description, DueDate
          FROM CourseAssignments
          WHERE CourseID = $CourseID and AssignmentName = '$assignmentName'";

// $UserID was stored when user has logged in 
// access students' Description on assignments
$sql_2 = "SELECT Grade, SubmissionTime, FilePath
          FROM Assignments
          WHERE StudentID = $UserID and AssignmentName = '$assignmentName'";

// Initialize an empty array to store the organized data
$assignmentsData = array();

// Fetch data for SQL query 1
$stmt = $pdo->query($sql_1);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // $dueDate = $row['DueDate'];
        
        // Check if the assignment is current or past based on the due date
        //$isCurrent = strtotime($dueDate) > time();
        
        $assignmentsData = array(
            'Description' => $row['Description'],
            'DueDate' => $row['DueDate'],
            'Grade' => -1, // Initialize Grade as -1
            'SubmissionTime' => null, // Initialize SubmissionTime as null
            'FilePath' => null // Store whether the assignment is current or past
        );
    }
    

// Fetch data for SQL query 2
$stmt = $pdo->query($sql_2);

// Debugging output
//echo "Number of rows fetched: " . $stmt->rowCount() . "<br>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $assignmentsData['Grade'] = $row['Grade'];
        //echo $assignmentsData['Grade'];
        $assignmentsData['SubmissionTime'] = $row['SubmissionTime'];
        $assignmentsData['FilePath'] = $row['FilePath'];
    
}

//print_r($assignmentsData);

// Close the database connection
$pdo = null;


$submissionStatus = null;
if ($assignmentsData['SubmissionTime'] == null) {
    $submissionStatus = "You have not submitted this assignment";
}else if ($assignmentsData['SubmissionTime'] <=  $assignmentsData['DueDate']){
    $submissionStatus = "You have submitted this assignment at " . $assignmentsData['SubmissionTime'];
}else{
    $submissionStatus = "You have passed the due date.";
}

$gradingStatus = null;
if ( $assignmentsData['Grade'] == -1 ){
    $gradingStatus = "The instructor has not grade your assignment yet";
}else if ( $assignmentsData['Grade'] >= 0)
    $gradingStatus = "Your grade is " . $assignmentsData['Grade'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>submitAssignemntPage</title>
</head>
<body>
    <!--Assignment name-->
    </h1><?php echo  $assignmentName?><h1>
    </h3><?php echo  $assignmentsData['DueDate']  ?><h3>
    </h3><?php echo  $submissionStatus ?><h3>
    </h3><?php echo  $gradingStatus ?><h3>
    </h3> Description: <h3>
    </h2> <?php echo  $assignmentsData['Description'] ?> <h2>
    
    <!-- File upload form -->
    <form id="uploadForm" action="uploadAssignment.php" method="post" enctype="multipart/form-data" onsubmit="uploadFile(event)">
    <label for="assignmentFile">Upload Assignment File (DOC, DOCX, TXT only):</label>
    <input type="file" name="assignmentFile" id="assignmentFile" accept=".doc, .docx, .txt">
    <input type="submit" value="Submit">
    </form>
    <div id="notification"></div>
    <?php
    if (isset($_SESSION['upload_message'])) {
        echo $_SESSION['upload_message'];
        unset($_SESSION['upload_message']); // Clear the session variable
    }
    ?>

</form>
</body>
</html>
