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
    
        $subject = "TestSubject";
        $courseNumber = "000";
        $section = "000";
        $credits = "10";
        $location = "HELL";

        $deleteQuery1 = "DELETE courses FROM courses WHERE Subject = '$subject' AND CourseNumber = '$courseNumber'";
        if ($conn->query($deleteQuery1) !== TRUE) {
            echo "Error deleting record: " . $conn->error;
        }

        
        $checkQuery = "SELECT * FROM courses WHERE Subject = '$subject' AND CourseNumber = '$courseNumber'";
        $result = $conn->query($checkQuery);
            $this->assertEquals(0, $result->num_rows, "Course was not deleted from the database.");
    
       
        $conn->close();
    }


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
    
        
        $subject = "TestSubject";
        $courseNumber = "000";
        $section = "000";
        $credits = "10";
        $location = "HELL";

        $creation = new courseCreation($conn);
        $result = $creation->createCourse($subject, $courseNumber, $section, $credits, $location);
        $this->assertEquals($result, "Course created.");

        $this->ResetCourse();

        $conn->close();



    }

    public function testCreateDuplicateCourse() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "aquademia";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }
    
        
        $subject = "TestSubject";
        $courseNumber = "000";
        $section = "000";
        $credits = "10";
        $location = "HELL";
        

        $creation = new courseCreation($conn);
        $insert = $creation->createCourse($subject, $courseNumber, $section, $credits, $location);
        $duplicateInsert = $creation->createCourse($subject, $courseNumber, $section, $credits, $location);
         
        $this->assertEquals($duplicateInsert, "Already exists.");
        $this->ResetCourse();

        $conn->close();

    }

}