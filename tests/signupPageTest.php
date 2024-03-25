<?php

namespace signupPage;
use PHPUnit\Framework\TestCase;
use mysqli;
use UserRegistration;
use mysqli_sql_exception;
use InvalidArgumentException;
include("..\The-Little-Mermaid\COSC - 310 - Little Mermaids - Aquademia\AquaDemia\Code\Assets\PHP\signupPage.php");

final class signupPageTest extends TestCase
{

    
    public function ResetStudent(){
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
        $systemType = "Student";

        $deleteQuery1 = "DELETE students FROM students JOIN users ON users.userID = students.studentID WHERE users.userName = '$userName'";
        if ($conn->query($deleteQuery1) !== TRUE) {
            echo "Error deleting record: " . $conn->error;
        }

        $deleteQuery2 = "DELETE FROM users WHERE username = '$userName'";
        if ($conn->query($deleteQuery2) !== TRUE) {
            echo "Error deleting record: " . $conn->error;
        }
        $checkQuery = "SELECT * FROM users WHERE username = '$userName'";
        $result = $conn->query($checkQuery);
            $this->assertEquals(0, $result->num_rows, "User was not deleted from the database.");
    
       
        $conn->close();
    }
    public function ResetTeacher(){
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

        $deleteQuery1 = "DELETE professors FROM professors JOIN users ON users.userID = professors.professorID WHERE users.userName = '$userName'";
        if ($conn->query($deleteQuery1) !== TRUE) {
            echo "Error deleting record: " . $conn->error;
        }

        $deleteQuery2 = "DELETE FROM users WHERE username = '$userName'";
        if ($conn->query($deleteQuery2) !== TRUE) {
            echo "Error deleting record: " . $conn->error;
        }
        $checkQuery = "SELECT * FROM users WHERE username = '$userName'";
$result = $conn->query($checkQuery);

$this->assertEquals(0, $result->num_rows, "User was not deleted from the database.");

        $conn->close();
    }

    public function testRegisterStudent(): void
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
        $email = "example@gmail.com";
        $phoneNumber = "1234567890";
        $userPassword = "TestPass";
        $systemType = "Student";

        $registration = new UserRegistration($conn);
        $result = $registration->registerUser($firstName, $lastName, $userName, $email, $phoneNumber, $userPassword, $systemType);
        $this->assertEquals($result, "Welcome to AquaDemia " . $firstName . " " . $lastName . " ");


       $this->resetStudent();

       
        $conn->close();



    }

    public function testRegisterTeacher(): void
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
        $email = "example@gmail.com";
        $phoneNumber = "1234567890";
        $userPassword = "TestPass";
        $systemType = "Teacher";

        $registration = new UserRegistration($conn);
        $result = $registration->registerUser($firstName, $lastName, $userName, $email, $phoneNumber, $userPassword, $systemType);
        $this->assertEquals($result, "Welcome to AquaDemia " . $firstName . " " . $lastName . " ");

        $this->ResetTeacher();

        $conn->close();



    }

    public function testRegisterDuplicateUser() {
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