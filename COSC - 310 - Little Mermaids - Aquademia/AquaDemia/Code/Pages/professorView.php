<?php 
session_start();
if ($_SESSION["UserType"] !== "Professor") {
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
    

    <div style="text-align:center; vertical-align:center; top: 0px;">
    <h1>Welcome, <?php echo $_SESSION["Username"] ?></h1>
        <button onclick="location.href = 'createAssignments.php';" type="button" class="button button1">Create Assignments</button>
        <button onclick="location.href = 'gradeAssignments.php';" type="button" class="button button1">Grade Assignments</button>
        <button onclick="location.href = 'editDetails.php';" type="button" class="button button1">Edit your details</button>
        <button onclick="location.href = 'login.html';" type="button" class="button button1">Logout</button>
    </div>

</body>
</html>