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
        $credits = "0";
        $location = "HELL";

        $deleteQuery1 = "DELETE FROM courses WHERE Subject = '$subject' AND CourseNumber = '$courseNumber'";
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
        $credits = "0";
        $location = "HELL";

        $creation = new courseCreation($conn);
        $result = $registration->createCourse($subject, $courseNumber, $section, $credits, $location);
        $this->assertEquals($result, "Course created");

        $this->ResetCourse();

        $conn->close();



    }

    public function testCreateDuplicateUser() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "aquademia";

        

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }
    
        
        $firstName = "TestUser";
        $lastName = "TestUser";
        $userName = $firstName . '_' . $lastName;
        $email = "example@gmail.com";
        $phoneNumber = "1234567890";
        $userPassword = "TestPass";
        $systemType = "Teacher";
try{
        $registration = new UserRegistration($conn);
        $Insert = $registration->registerUser($firstName, $lastName, $userName, $email, $phoneNumber, $userPassword, $systemType);
        $DuplicateInsert =  $registration->registerUser($firstName, $lastName, $userName, $email, $phoneNumber, $userPassword, $systemType);
} 
    catch(mysqli_sql_exception $exception){
        $this->assertTrue(True);
        $this->ResetTeacher();
        $conn->close();
        return;
    }
    $this->assertTrue(False);
    $this->ResetTeacher();
    $conn->close();



}
public function testNullInsertion(): void
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "aquademia";

        

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }
    
        
        $firstName = "TestUser";
        $lastName = "TestUser";
        $userName = $firstName . '_' . $lastName;
        $email = null;
        $phoneNumber = null;
        $userPassword = "TestPass";
        $systemType = "Teacher";

        $registration = new UserRegistration($conn);
        $nullInsert = $registration->registerUser($firstName, $lastName, $userName, $email, $phoneNumber, $userPassword, $systemType);
        $this->assertStringStartsWith("Please fill all fields", $nullInsert);
        $this->ResetTeacher();
        $conn->close();
}
}