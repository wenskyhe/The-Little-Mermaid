
<?php
session_start();
require_once 'dbh.inc.php';
require_once '..\..\Code\Assets\PHP\assignmentListFcn.php';


if (!isset($_SESSION["UserID"])) {
    echo "_SESSION[UserID] is not setted";
    header("Location: ../../Code/");
} else {
    echo "_SESSION[UserID] is " . $_SESSION["UserID"];
    $UserID = $_SESSION["UserID"];
}

if (isset($_GET['courseID']) && !empty($_GET['courseID'])) {
    $courseID = $_GET['courseID'];
    $_SESSION['courseID'] = $courseID;
    echo "'courseID' is set to" . " $courseID by get ";
} else {
    $courseID = $_SESSION['courseID'];
    echo "'courseID' is set to" . " $courseID by SESSION ";
}

$assignmentsData = getAssignmentsData($pdo, $courseID, $UserID);

$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment List</title>
    <link rel="stylesheet" href="../Assets/CSS/central.css">
    <style>
    body {
        margin-left: 5%;
        margin-right: 5%;
    }
    </style>
</head>
<body>
<p style="text-align: right;">
    <a href="studentView.php"><?php echo $_SESSION["Username"] ?></a><?php echo "  " ?>
    <a href="login.html">Logout</a>
</p>
    <h1 style='color:#deb9fb ;'>Assignments</h1>
    <p style='color: white;'><?php if (empty($assignmentsData)) {
        echo "The teacher did not create any assignment. ";
    } ?>
    </p>

    <h2 style='color:#deb9fb ;'>Current</h2>

    <ul>
        <?php foreach ($assignmentsData as $assignmentID => $assignmentDetails) {
        if ($assignmentDetails['IsCurrent']) {
            $assignmentLink = "submitAssignmentPage.php?assignmentID=" . urlencode($assignmentID);
            echo "<li style='color: white;'>
            <a href='$assignmentLink'>
            {$assignmentDetails['assignmentName']}</a> 
            Grade: {$assignmentDetails['Grade']} <br> 
                Due Date: {$assignmentDetails['DueDate']} , Submission Time: {$assignmentDetails['SubmissionTime']}
            </li>";
        }
} ?>

    </ul>

    <h2 style='color:#deb9fb ;'>Past</h2>
    <ul>
        <?php foreach ($assignmentsData as $assignmentID => $assignmentDetails) {
            if (!$assignmentDetails['IsCurrent']) {
                $assignmentLink = "submitAssignmentPage.php?assignmentID=" . urlencode($assignmentID);
                echo "<li style='color: white;'>
                <a href='$assignmentLink'>
                {$assignmentDetails['assignmentName']}</a> 
                Grade: {$assignmentDetails['Grade']} <br> 
                Due Date: {$assignmentDetails['DueDate']} , Submission Time: {$assignmentDetails['SubmissionTime']}
                </li>";
            }
        } ?>
    </ul>
</body>
</html>
