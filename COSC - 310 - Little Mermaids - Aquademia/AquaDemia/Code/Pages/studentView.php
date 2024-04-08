<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student View</title>
    <link rel="stylesheet" href="../Assets/CSS/central.css">
    <style>
    </style>
</head>
<body>
    <h1 style="text-align: center; color:#deb9fb ;">Welcome, <?php echo $_SESSION["Username"] ?></h1>
    <h3 style="text-align: center;">
    <a href="editDetails.php">Edit my details</a><br><br>
    <a href="registerCourse.html">Register for a course</a><br><br>
    <a href="studentMP.php">View my courses</a><br><br>
    <a href="login.html">Logout</a>
    </h3>
</body>
</html>
