<?php

final class UserRegistration {
    
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function registerUser($firstName,$lastName,$userName,$email,$phoneNumber,$userPassword,$systemType) {
        
        if (empty($firstName) || empty($lastName) || empty($email) || empty($phoneNumber) || empty($userPassword) || empty($systemType)) {
            return "Please fill all fields (statement triggers regardless of unit test success)   ";
        }
    
        $stmt = $this->conn->prepare("INSERT INTO Users (userType, firstName, lastName, userName, email, phoneNumber, passwordHash) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $systemType, $firstName, $lastName, $userName, $email, $phoneNumber, $userPassword);
    
        if ($stmt->execute()) {
            $stmt->close();
            header('Location: ../../Pages/login.html');
            return "Welcome to AquaDemia " . $firstName . " " . $lastName . " ";
            
        } else {
            $stmt->close();
            return "Error: " . $stmt->error;
        }
    }
}

// $servername = "aqb.h.filess.io";
// $username = "aquademia_streetage"; // default XAMPP MySQL username
// $password = "5c6a6f0224cbcb0385cbde21f8a5f83bf7c37b42"; // default XAMPP MySQL password is empty
// $dbname = "aquademia_streetage";
// $port = "3307";


// $conn = new mysqli($servername, $username, $password, $dbname, $port);


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aquademia";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

$firstName = $conn->real_escape_string($_POST["fname"]);
$lastName = $conn->real_escape_string($_POST["lname"]);
$userName = $firstName . '_' . $lastName;
$email = $conn->real_escape_string($_POST["email"]);
$phoneNumber = $conn->real_escape_string($_POST["phoneNumber"]);
$userPassword = $conn->real_escape_string($_POST["confirmPassword"]);
$systemType = $conn->real_escape_string($_POST["TeacherOrStudent"]);


$registration = new UserRegistration($conn);
$postData = $_POST; 
$result = $registration->registerUser($firstName,$lastName,$userName,$email,$phoneNumber, $userPassword, $systemType);
echo $result;

$conn->close();

?>