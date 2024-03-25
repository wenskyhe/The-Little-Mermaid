<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses</title>
    <link rel="stylesheet" href="../Assets/CSS/central.css">
</head>
<body>
    <header>
        <div class="container">
            <img src="../Assets/Images/logo.png" width="60" height="75" class="logo">
            <navBar>
                <navBarElements>
                    <li><a href="studentView.php">My Courses</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </navBarElements>
            </navBar>
        </div>
    </header>
    <div style="padding: 20px;">
        <h1>Registered Courses & Grades</h1>
        <?php
        // Placeholder: Connect to the database
        $conn = new mysqli("localhost", "username", "password", "dbname");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Placeholder: Fetch courses and grades for the logged-in student
        $studentId = $_SESSION['studentId']; // Assuming studentId is stored in session
        $sql = "SELECT courses.courseName, assignments.assignmentName, grades.grade 
                FROM courses 
                JOIN registrations ON courses.id = registrations.courseId 
                JOIN assignments ON courses.id = assignments.courseId 
                JOIN grades ON assignments.id = grades.assignmentId 
                WHERE registrations.studentId = '$studentId'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data for each row
            while($row = $result->fetch_assoc()) {
                echo "<div><strong>Course:</strong> " . $row["courseName"]. " - <strong>Assignment:</strong> " . $row["assignmentName"]. " - <strong>Grade:</strong> " . $row["grade"]. "</div>";
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>
    </div>
    <footer>
        <button onclick="location.href='studentView.php'" class="button button1">Back to My Courses</button>
    </footer>
</body>
</html>
