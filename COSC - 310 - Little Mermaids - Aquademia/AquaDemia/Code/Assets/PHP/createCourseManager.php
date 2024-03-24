<?php

    $servername = "localhost";
    $username = "root"; // default XAMPP MySQL username
    $password = ""; // default XAMPP MySQL password is empty
    $dbname = "aquademia";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection Failed". $conn->connect_error);
    }

    $subject = $conn -> real_escape_string($_POST["subject"]);
    $courseNumber = $conn -> real_escape_string($_POST["courseNumber"]);
    $section = $conn -> real_escape_string($_POST["section"]);
    $credits = $conn -> real_escape_string($_POST["profID"]);
    $location = $conn -> real_escape_string($_POST["location"]);

    $stmt = $conn -> prepare("INSERT INTO Courses (Subject, CourseNumber, Section, Credits, Location) VALUES (?,?,?,?,?)"); // Corrected SQL statement
    $stmt ->bind_param("sssss",$subject, $courseNumber, $section, $credits, $location);

    //Send an alert that the course has been created
    if ($stmt->execute()) {
        echo '<script>
        alert("Course created!");
        window.location.href="../../Pages/createCourses.html";
        </script>';
        
    } else {
     echo "Error: ". $stmt->error;
    }

    $stmt->close();
    $conn->close();

?>