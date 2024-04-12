<?php 
session_start();

// Redirect unauthorized users to the login page
if ($_SESSION["UserType"] !== "Admin") {
    header("Location: unauthorizedAccess.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../Assets/CSS/central.css">
</head>
<body>
    
    <h1>Welcome, <?php echo $_SESSION["Username"] ?></h1>
    <div style="text-align:center; vertical-align:center; top: 0px;">
        <button onclick="location.href = 'createCourses.html';" type="button" class="button button1">Create a course</button>
        <button onclick="location.href = 'editDetails.php';" type="button" class="button button1">Edit your details</button>
        <button onclick="location.href = 'acceptStudent.php';" type="button" class="button button1">Accept students into course</button>
        <button onclick="location.href = 'login.html';" type="button" class="button button1">Logout</button>
    </div>

</body>
</html>