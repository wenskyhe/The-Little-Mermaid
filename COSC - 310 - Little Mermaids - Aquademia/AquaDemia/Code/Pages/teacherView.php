<?php 
session_start();
if ($_SESSION["Role"] !== "Teacher") {
    header("Location: unauthorizedAccess.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../Assets/CSS/central.css">
</head>
<body>
    <h1 style="text-align: center;">Welcome, <?php echo $_SESSION["Username"] ?></h1>
    
    <div style="text-align: center; padding-top: 20px;">
        <a href="createAssignments.php">Create Assignments</a><br>
        <a href="gradeAssignments.php">Grade Assignments</a><br>
        <a href="viewCourses.php">View Your Courses</a><br>
        <a href="editDetails.php">Edit Your Details</a><br>
    </div>

</body>
</html>
