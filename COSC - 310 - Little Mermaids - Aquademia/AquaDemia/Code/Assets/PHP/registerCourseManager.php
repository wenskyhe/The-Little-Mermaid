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
    $studentId = $_POST['studentId'];

    $courseId = $conn->real_escape_string($courseId);
    $studentId = $conn->real_escape_string($studentId);

    $query = "SELECT * FROM course_registrations WHERE courseId = '$courseId' AND studentId = '$studentId'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
 
        echo "<script>alert('You are already registered for this course.'); window.location.href='../registerCourse.php';</script>";
    } else {

        $insertQuery = "INSERT INTO course_registrations (courseId, studentId) VALUES ('$courseId', '$studentId')";
        
        if ($conn->query($insertQuery) === TRUE) {

            echo "<script>alert('Registration successful.'); window.location.href='../studentView.php';</script>";
        } else {

            echo "<script>alert('Error registering for course: " . $conn->error . "'); window.location.href='../registerCourse.php';</script>";
        }
    }
    
    $conn->close();
} else {
    // Redirect to the registration form if the page is accessed directly without posting form data
    header("Location: ../registerCourse.html");
    exit();
}
?>
