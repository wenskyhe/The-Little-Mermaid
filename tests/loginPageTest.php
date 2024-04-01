<?php

namespace loginPage;
use PHPUnit\Framework\TestCase;
use PDO;
use mysqli;
use userLogin;
use mysqli_sql_exception;
use InvalidArgumentException;
include("..\The-Little-Mermaid\COSC - 310 - Little Mermaids - Aquademia\AquaDemia\Code\Assets\PHP\loginPage.php");

final class LoginPageTest extends TestCase{

    
public function testValidStudentLogin(){
    session_save_path();
    session_start(); // Start the session.
    $servername = "localhost";
    $username = "root"; // default XAMPP MySQL username
    $password = ""; // default XAMPP MySQL password is empty
    $dbname = "aquademia";
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    if ($conn->errorCode()) {
        die("Connection Failed: " . $conn->errorCode());
    }
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $newLogin = new userLogin($conn);
    $uname = "test_student";
    $psw = "student";

    $result = $newLogin->loginAttempt($conn,$uname,$psw);
    $this->assertStringStartsWith($result,"login code:2");
    $conn = NULL; //null is how to close PDO connections
}

public function testValidAdminLogin(){
    session_save_path();
    session_start(); // Start the session.
    $servername = "localhost";
    $username = "root"; // default XAMPP MySQL username
    $password = ""; // default XAMPP MySQL password is empty
    $dbname = "aquademia";
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    if ($conn->errorCode()) {
        die("Connection Failed: " . $conn->errorCode());
    }
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $newLogin = new userLogin($conn);
    $uname = "test_admin";
    $psw = "admin";

    $result = $newLogin->loginAttempt($conn,$uname,$psw);
    $this->assertStringStartsWith($result,"login code:1");
    $conn = NULL; //null is how to close PDO connections
}
public function testValidTeacherLogin(){
    session_save_path();
    session_start(); // Start the session.
    $servername = "localhost";
    $username = "root"; // default XAMPP MySQL username
    $password = ""; // default XAMPP MySQL password is empty
    $dbname = "aquademia";
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    if ($conn->errorCode()) {
        die("Connection Failed: " . $conn->errorCode());
    }
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $newLogin = new userLogin($conn);
    $uname = "test_teacher";
    $psw = "teacher";

    $result = $newLogin->loginAttempt($conn,$uname,$psw);
    $this->assertStringStartsWith($result,"login code:3");
    $conn = null; //null is how to close PDO connections    
}

public function testinvalidLogin(){
    session_save_path();
    session_start(); // Start the session.
    $servername = "localhost";
    $username = "root"; // default XAMPP MySQL username
    $password = ""; // default XAMPP MySQL password is empty
    $dbname = "aquademia";
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    if ($conn->errorCode()) {
        die("Connection Failed: " . $conn->errorCode());
    }
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $newLogin = new userLogin($conn);
    $uname = "test_student";
    $psw = null;

    $result = $newLogin->loginAttempt($conn,$uname,$psw);
    // $this->assertStringStartsWith($result,"login code:4->error");
    $conn = NULL; //null is how to close PDO connections
    $this->assertEquals(0,1);
}

}