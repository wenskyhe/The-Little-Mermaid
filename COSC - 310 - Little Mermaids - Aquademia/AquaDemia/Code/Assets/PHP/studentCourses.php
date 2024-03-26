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
            // Assuming session_start() and database connection are already done in header or a required file
            $studentId = $_SESSION['studentId']; // The logged-in student ID
            $query = "SELECT c.CourseID, c.Subject, c.CourseNumber, c.Section FROM Courses c JOIN studentRegistration sr ON c.CourseID = sr.CourseID WHERE sr.StudentID = ?";
            
            // Prepared statement to prevent SQL injection
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("i", $studentId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<p>" . htmlspecialchars($row['Subject']) . " " . htmlspecialchars($row['CourseNumber']) . " Section " . htmlspecialchars($row['Section']) . " - <a href='courseDetails.php?courseId=" . urlencode($row['CourseID']) . "'>Details</a></p>";
                    }
                } else {
                    echo "<p>No courses found.</p>";
                }
            } else {
                echo "<p>Error preparing query: " . htmlspecialchars($conn->error) . "</p>";
            }
            ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 AquaDemia. All rights reserved.</p>
    </footer>
</body>
</html>
