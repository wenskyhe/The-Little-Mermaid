<?php

final class courseCreation{
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function alreadyExists($subject, $courseNumber){
        $query = "SELECT Subject, CourseNumber FROM Courses WHERE Subject = ? AND CourseNumber = ? LIMIT 1";
        $result = $this -> conn -> execute_query($query, [$subject, $courseNumber]);
        if($result->num_rows == 1) {
            //found
            return true;
        }
        return false;
    }

    public function createCourse($subject, $courseNumber, $section, $credits, $location){
        if (empty($subject) || empty($courseNumber) || empty($section) || empty($credits) || empty($location)) {
            return "Please fill all fields";
        }

        if($this-> alreadyExists($subject, $courseNumber)){
            return "Already exists.";
            
        }

        $stmt = $this-> conn -> prepare("INSERT INTO Courses (Subject, CourseNumber, Section, Credits, Location) VALUES (?,?,?,?,?)"); // Corrected SQL statement
        $stmt ->bind_param("sssss",$subject, $courseNumber, $section, $credits, $location);
    
        //Send an alert that the course has been created
        if ($stmt->execute()) {
          return "Course created.";
        } else {
            $stmt->close();
            return "Error: ". $stmt->error;
        }
    }

    
}

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

    $creation = new courseCreation($conn);
    $postData = $_POST;
    $result = $creation->createCourse($subject, $courseNumber, $section, $credits, $location);

    if($result == "Course created."){
        echo '<script>
            alert("Course created!");
            </script>';
    }
    elseif($result == "Already exists."){
        echo '<script>
        alert("Course already exists!");
        </script>';
    }
    echo '<script>
        window.location.href="../../Pages/createCourses.html"</script>';

    
    $conn->close();

    
?>