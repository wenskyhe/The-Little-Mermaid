<?php

namespace registerCourse;
use PHPUnit\Framework\TestCase;
use PDO;
use mysqli;
use courseRegistration;
use mysqli_sql_exception;
use InvalidArgumentException;
// include("..\The-Little-Mermaid\COSC - 310 - Little Mermaids - Aquademia\AquaDemia\Code\Assets\PHP\acceptEnrollment.php");
// include("../The-Little-Mermaid/COSC - 310 - Little Mermaids - Aquademia/AquaDemia/Code/Asset/PHP/rejectEnrollment.php");

include("../The-Little-Mermaid/COSC - 310 - Little Mermaids - Aquademia/AquaDemia/Code/Assets/PHP/registerCourseManager.php");

final class courseRegistrationTest extends TestCase{


    public function ResetRegistration(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "aquademia";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }
    
        $userID = 696969;   //highly specific user id to ensure we don't manipulate with genuine records
        $courseID = 1;      // need an actual course number

        $deleteQuery1 = "DELETE FROM enrollment WHERE UserID = '$userID' AND CourseID = '$courseID'";
        if ($conn->query($deleteQuery1) !== TRUE) {
            echo "Error deleting record: " . $conn->error;
        }
       
        $conn->close();
    }
    
public function testValidRegistration(){
    session_save_path();
    session_start(); // Start the session.
    $servername = "localhost";
    $username = "root"; // default XAMPP MySQL username
    $password = ""; // default XAMPP MySQL password is empty
    $dbname = "aquademia";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection Failed". $conn->connect_error);
    }

    $newRegistration = new courseRegistration($conn);
    $userID = 696969;   //highly specific user id to ensure we don't manipulate with genuine records
    $courseID = 1;      // need an actual course number

    $result = $newRegistration->registerCourse($userID, $courseID,$conn);
    $this->assertStringStartsWith($result,"Enrollment created.");
    // $conn = NULL; //null is how to close PDO connections

    // We have to delete the course now too
    $this->resetRegistration();
    $conn->close();
}


public function testAlreadyEnrolled(){
    session_save_path();
    session_start(); // Start the session.
    $servername = "localhost";
    $username = "root"; // default XAMPP MySQL username
    $password = ""; // default XAMPP MySQL password is empty
    $dbname = "aquademia";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection Failed". $conn->connect_error);
    }
    
    $newRegistration = new courseRegistration($conn);
    $userID = 696969;   //highly specific user id to ensure we don't manipulate with genuine records
    $courseID = 1;      // need an actual course number

    $enrollment1 = $newRegistration->registerCourse($userID, $courseID,$conn);
    $enrollment2 = $newRegistration->registerCourse($userID, $courseID,$conn);

    $this->assertEquals($enrollment2, "Already enrolled.");

    // We have to delete the course now too
    $this->resetRegistration();
    $conn->close();
}

public function testInvalidCourseID(){
    session_save_path();
    session_start(); // Start the session.
    $servername = "localhost";
    $username = "root"; // default XAMPP MySQL username
    $password = ""; // default XAMPP MySQL password is empty
    $dbname = "aquademia";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection Failed". $conn->connect_error);
    }
    
    $newRegistration = new courseRegistration($conn);
    $userID = 696969;       //highly specific user id to ensure we don't manipulate with genuine records
    $courseID = 696969;      // highly specific unlikley course id to ensure it doesn't exist

    $enrollment = $newRegistration->registerCourse($userID, $courseID,$conn);

    $this->assertEquals($enrollment, "Course does not exist.");

    // We have to delete the course now too
    $this->resetRegistration();
    $conn->close();
}

}