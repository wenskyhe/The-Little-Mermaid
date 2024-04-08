
<?php

// ruby
include_once 'config.php';
include_once 'dbh.inc.php';
require_once '..\Assets\PHP\cn_assignmentList.php';

$UserID = $_SESSION['UserID'];
$CourseID = $_GET['CourseID'];

$assignmentsData = getAssignmentData($pdo, $CourseID, $UserID);

$pdo = null;
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
    <?php if (empty($assignmentsData)) {
        echo "The teacher did not create any assignment. ";
    } ?>

    <h2>Current</h2>
    <ul>
        <?php foreach ($assignmentsData as $assignmentID => $assignmentDetails) {
        if ($assignmentDetails['IsCurrent']) {
            $assignmentLink = "fcn_submitAssignmentPage.php?assignmentID=" . urlencode($assignmentID) . "&dueDate=" . urlencode($assignmentDetails['DueDate']);
            echo "<li><a href='$assignmentLink'>{$assignmentDetails['assignmentName']}</a> (Due Date: {$assignmentDetails['DueDate']}) <br> Grade: {$assignmentDetails['Grade']}, Submission Time: {$assignmentDetails['SubmissionTime']}</li>";
        }
} ?>

    </ul>

    <h2>Past</h2>
    <ul>
        <?php foreach ($assignmentsData as $assignmentID => $assignmentDetails) {
            if (!$assignmentDetails['IsCurrent']) {
                $assignmentLink = "fcn_submitAssignmentPage.php?assignmentID=" . urlencode($assignmentID) . "&dueDate=" . urlencode($assignmentDetails['DueDate']);
                echo "<li><a href='$assignmentLink'>{$assignmentDetails['assignmentName']}</a> (Due Date: {$assignmentDetails['DueDate']}) <br> Grade: {$assignmentDetails['Grade']}, Submission Time: {$assignmentDetails['SubmissionTime']}</li>";
            }
        } ?>
    </ul>
</body>
</html>
