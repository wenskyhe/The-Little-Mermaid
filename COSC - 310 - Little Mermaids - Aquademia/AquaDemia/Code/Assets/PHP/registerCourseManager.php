<?php

    session_start();

    $servername = "localhost";
    $username = "root"; // default XAMPP MySQL username
    $password = ""; // default XAMPP MySQL password is empty
    $dbname = "aquademia";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection Failed". $conn->connect_error);
    }

    $user_name = $_SESSION["Username"];
    $userID =  $_SESSION["UserID"];
    $courseID = $conn -> real_escape_string($_POST["courseId"]);
 
    $stmt = $conn -> prepare("INSERT INTO Enrollment (UserID, CourseID, EnrollmentDate, Accepted) VALUES (?,?,CURDATE(),0)");
    $stmt ->bind_param("ss",$userID, $courseID);


    //Send an alert that the registration has been completed
    if ($stmt->execute()) {
        echo '<script>
        alert("Registration is now pending");
        window.location.href="../../Pages/registerCourse.html";
        </script>';
    } else {
     echo "Error: ". $stmt->error;
    }

    $stmt->close();
    $conn->close();

?>
