<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin View</title>
    <link rel="stylesheet" href="../Assets/CSS/central.css">
</head>
<body>
    <h1 style="text-align: center;">Welcome, <?php echo $_SESSION["Username"] ?></h1>
    
    <a href="editDetails.php">Edit your details</a><br>
    <a href="registerCourse.php">Register for a course</a><br>
    <a href="viewCourses.php">View Your Courses</a><br>
    <a href="gradeAssignments.php">Assignments Graded</a><br>

    
</body>
</html>