<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student View</title>
    <!-- <link rel="stylesheet" href="../Assets/CSS/bootstrap.min.css"> -->
    <link rel="stylesheet" href="../Assets/CSS/central.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    </style>
</head>
<body>

<header>  
        <div class="container">
          <img src="../Assets/Images/logo.png" width="60" height="auto" class = "logo">
            <navBar>
              <navBarElements>
              <li>
                    <a href="studentMP.php">
                    <div>
                        <i class='fa fa-bars' style='font-size:36px; color:white;'></i>
                        Courses
                    </div>
                </li>
                <li>
                    <a href="registerCourse.html">
                    <div>
                        <i class='fa fa-plus' style='font-size:36px; color:white;'></i>
                        Register
                    </div>
                </li>
                <li>
                    <a href="editDetails.php">
                    <div>
                        <i class='fa fa-user-circle' style='font-size:36px; color:white;'></i>
                        Profile
                    </div>
                </li>
                <li><a href="login.html">Logout</a></li>
              </navBarElements>
            </navbar>
        </div>
</header>


    <h1 style="text-align: center; color:#deb9fb ;">Welcome, <?php echo $_SESSION["Username"] ?></h1>
    <h3 style="text-align: center;">
    <a href="editDetails.php">Edit my details</a><br><br>
    <a href="registerCourse.html">Register for a course</a><br><br>
    <a href="studentMP.php">View my courses</a><br><br>
    <a href="login.html">Logout</a>
    </h3>
</body>
</html>
