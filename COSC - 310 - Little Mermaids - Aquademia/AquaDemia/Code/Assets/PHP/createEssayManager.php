<?php

final class essayCreation{
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // public function alreadyExists($courseName){
    //     $query = "SELECT CourseName FROM Courses WHERE CourseName = ? LIMIT 1";
    //     $result = $this -> conn -> execute_query($query, [$courseName]);
    //     if($result->num_rows == 1) {
    //         //found
    //         return true;
    //     }
    //     return false;
    // }


    public function createEssay($courseID, $essayTitle, $essayDescription, $dueDate, $totalMarks){

        $stmt = $this-> conn -> prepare("INSERT INTO assignments (courseID, title, description, dueDate, weight, type, visibilityStatus, assignmentFilePath) VALUES (?,?,?,?,?,'essay', 1, NULL)");
        $stmt ->bind_param("sssss",$courseID, $essayTitle, $essayDescription, $dueDate, $totalMarks);
    
        //Send an alert that the course has been created
        if ($stmt->execute()) {
          return "Essay created.";
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

    $courseID = $conn -> real_escape_string($_POST["courseID"]);
    $title = $conn -> real_escape_string($_POST["assignmentTitle"]);
    $description = $conn -> real_escape_string($_POST["assignmentDescription"]);
    $dueDate = $conn -> real_escape_string($_POST["deadline"]);
    $totalMarks = $conn -> real_escape_string($_POST["assignmentMarks"]);

    $creation = new essayCreation($conn);
    $result = $creation->createEssay($courseID, $title, $description, $dueDate, $totalMarks);

    if($result == "Essay created."){
        echo '<script>
            alert("Assignment created!");
            </script>';
    }
    echo '<script>
        window.location.href="../../Pages/createAssignments.php"</script>';

    
    $conn->close();

    
?>