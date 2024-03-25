<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aquademia";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courseId = $_POST['courseId'];
    $courseId = $conn->real_escape_string($courseId);

    $query = "INSERT INTO course_registrations (courseId) VALUES ('$courseId')";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Registration successful.'); window.location.href='../studentView.php';</script>";
    } else {
        echo "<script>alert('Error registering for course: " . $conn->error . "'); window.location.href='../registerCourse.php';</script>";
    }
    
    $conn->close();
} else {
    header("Location: ../registerCourse.html");
    exit();
}
?>
