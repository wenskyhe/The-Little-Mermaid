<?php
// Assuming session_start() and database connection are included at the top of every script
require 'path_to_your_database_connection_script.php';
session_start();

// Check if the user is logged in as a student
if ($_SESSION['userType'] != 'Student') {
    // If not a student, redirect to the appropriate page
    header("Location: loginPage.php");
    exit();
}

$studentId = $_SESSION['studentId']; // Using the logged-in student's ID from the session

?>
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
            <nav>
                <ul>
                    <li><a href="studentView.php">My Courses</a></li>
                    <li><a href="submitAssignment.html">Submit Assignment</a></li>
                    <li><a href="viewGrades.php">View Grades</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <h1>Registered Courses</h1>
        <div id="coursesContainer">
            <?php
            // Fetch courses from the database
            $stmt = $conn->prepare("SELECT CourseID, Subject, CourseNumber, Section FROM Courses WHERE StudentID = ?");
            $stmt->bind_param("i", $studentId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<p>" . htmlspecialchars($row['Subject']) . " " . htmlspecialchars($row['CourseNumber']) . " Section " . htmlspecialchars($row['Section']) . " - <a href='courseDetails.php?courseId=" . urlencode($row['CourseID']) . "'>Details</a></p>";
                }
            } else {
                echo "<p>You are not registered for any courses.</p>";
            }

            $stmt->close();
            ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 AquaDemia. All rights reserved.</p>
    </footer>
</body>
</html>
