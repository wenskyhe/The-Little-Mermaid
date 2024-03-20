<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for a Course</title>
    <link rel="stylesheet" href="../Assets/CSS/central.css">
</head>
<body>
    <header>
        <div class="container">
            <img src="../Assets/Images/logo.png" width="60" height="75" class = "logo">
            <nav>
                <ul>
                    <li><a href="#">Classes</a></li>
                    <li><a href="#">Assignments</a></li>
                    <li><a href="#">Grades</a></li>
                    <li><a href="#">Settings</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div style="padding-left: 10%;">
        <h1 style="padding-top: 5%;">Register for a Course</h1>
        <form action="../Assets/PHP/registerCourseManager.php" method="post">
            <input type="text" placeholder="Enter Course ID" id="courseId" name="courseId"><br>
            <input type="text" placeholder="Enter Student ID" id="studentId" name="studentId"><br>
            <button class="button button1" style="margin-top: 5%;">Register</button>
        </form>
    </div>
</body>
</html>
