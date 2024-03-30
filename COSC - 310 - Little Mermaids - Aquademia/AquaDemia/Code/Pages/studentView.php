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
</head>
<body>
    <h1 style="text-align: center;">Welcome, <?php echo $_SESSION["Username"] ?></h1>
    
    <a href="registerCourse.html">Register for a course</a><br>
    <a href="editDetails.php">Edit your details</a><br>
    <a href="login.html">Logout</a>
    <a href="studentMP.php">studentMP</a>
    
</body>
</html>