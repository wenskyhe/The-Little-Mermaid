<?php 
session_start();

$usertype = $_SESSION["UserType"];
// Only display this page if the user is an admin;
if($usertype == "Admin"){
    echo '

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin View</title>
    <link rel="stylesheet" href="../Assets/CSS/central.css">
</head>
<body>
    <h1 style="text-align: center;">Welcome, '. $_SESSION["Username"] . '</h1>
    
    <a href="createCourses.html">Create a course</a><br>
    <a href="editDetails.php">Edit your details</a><br>
    <a href="acceptStudent.php">Accept students into course</a>


</body>
</html>
';
}

//Else if the user is not an admin, give them an error;
else {echo 'ERROR! You must be an admin to access this page! <br>';
    echo '<a href="login.html">Go back to login</a>';}
?>