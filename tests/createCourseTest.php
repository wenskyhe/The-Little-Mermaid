<?php

namespace createCourse;
use PHPUnit\Framework\TestCase;
use mysqli;
use courseCreation;
use mysqli_sql_exception;
use InvalidArgumentException;
include("..\The-Little-Mermaid\COSC - 310 - Little Mermaids - Aquademia\AquaDemia\Code\Assets\PHP\createCourseManager.php");

final class createCourseTest extends TestCase
{

    
    public function ResetCourse(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "aquademia";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }
    
        $courseName = "TEST123";
        $courseDescription = "BLANK";
        $prereqs = "";
        $profID = 3;

        $deleteQuery1 = "DELETE courses FROM courses WHERE CourseName = '$courseName'";
        if ($conn->query($deleteQuery1) !== TRUE) {
            echo "Error deleting record: " . $conn->error;
        }

        
        $checkQuery = "SELECT * FROM courses WHERE CourseName = '$courseName'";
        $result = $conn->query($checkQuery);
            $this->assertEquals(0, $result->num_rows, "Course was not deleted from the database.");
    
       
        $conn->close();
    }

    // Test to create a sample course: should pass
    public function testCreateCourse(): void
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "aquademia";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }

        $courseName = "TEST123";
        $courseDescription = "BLANK";
        $prereqs = "";
        $profID = 3;

        $creation = new courseCreation($conn);
        $result = $creation->createCourse($courseName, $courseDescription, $prereqs, $profID);
        $this->assertEquals($result, "Course created.");

        $this->ResetCourse();

        $conn->close();
    }

    // Test to add a course that already exists. Should not add course
    public function testCreateDuplicateCourse() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "aquademia";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }
        
        $courseName = "TEST123";
        $courseDescription = "BLANK";
        $prereqs = "";
        $profID = "3";
        
        $creation = new courseCreation($conn);
        $insert = $creation->createCourse($courseName, $courseDescription, $prereqs, $profID);
        $duplicateInsert = $creation->createCourse($courseName, $courseDescription, $prereqs, $profID);
         
        $this->assertEquals($duplicateInsert, "Already exists.");
        $this->ResetCourse();

        $conn->close();

    }

    // Test to add a course when there is no matching professorID. Should return that prof is invald
    public function testProfInvalid(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "aquademia";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }

        $courseName = "TEST123";
        $courseDescription = "BLANK";
        $prereqs = "";
        $profID = 2;

        $creation = new courseCreation($conn);
        $result = $creation->createCourse($courseName, $courseDescription, $prereqs, $profID);
        $this->assertEquals($result, "Invalid professor.");

        $this->ResetCourse();

        $conn->close();
    }

}