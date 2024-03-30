<?php

final class courseCreation{
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function alreadyExists($courseName){
        $query = "SELECT CourseName FROM Courses WHERE CourseName = ? LIMIT 1";
        $result = $this -> conn -> execute_query($query, [$courseName]);
        if($result->num_rows == 1) {
            //found
            return true;
        }
        return false;
    }

    public function checkProf($profID){
        $query = "SELECT professorID FROM professors WHERE professorID = ?";
        $result = $this -> conn -> execute_query($query, [$profID]);
        if($result->num_rows == 1) {
            //found
            return true;
        }
        return false;

    }

    public function createCourse($courseName, $courseDescription, $prereqs, $profID){
        if($this-> alreadyExists($courseName)){
            return "Already exists."; 
        }

        if(!$this->checkProf($profID)){
            return "Invalid professor.";
        }

        $stmt = $this-> conn -> prepare("INSERT INTO Courses (CourseName, CourseDescription, CoursePrequisiteID, ProfessorID, isCourseActive) VALUES (?,?,?,?,1)");
        $stmt ->bind_param("ssss",$courseName, $courseDescription, $prereqs, $profID);
    
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
    $courseName = $subject . $courseNumber;
    $courseDescription = $conn -> real_escape_string($_POST["description"]);
    $prereqs = $conn -> real_escape_string($_POST["prereqs"]);
    $profID = $conn -> real_escape_string($_POST["profID"]);

    $creation = new courseCreation($conn);
    $result = $creation->createCourse($courseName, $courseDescription, $prereqs, $profID);

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