<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aquademia";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection Failed". $conn->connect_error);
}

$firstName = $conn ->real_escape_string($_POST["fname"]);
$lastName = $conn ->real_escape_string($_POST["lname"]);
$userName = $firstName . '_' . $lastName;
$email = $conn ->real_escape_string($_POST["email"]);
$phoneNumber = $conn ->real_escape_string($_POST["phoneNumber"]); // Corrected variable name
$userPassword = $conn->real_escape_string($_POST["confirmPassword"]);
$systemType = $conn ->real_escape_string($_POST["TeacherOrStudent"]);

// $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn -> prepare("INSERT INTO Users (userType, firstName, lastName, userName, email, phoneNumber, passwordHash) VALUES (?, ?, ?, ?, ?, ?, ?)"); // Corrected SQL statement
$stmt ->bind_param("sssssss", $systemType, $firstName, $lastName, $userName, $email, $phoneNumber, $userPassword);

if ($stmt->execute()) {
    echo "Welcome to AquaDemia ". $firstName ." ". $lastName ." ";
    header("Location: ../../Pages/login.html");
} else {
    echo "Error: ". $stmt->error;
}

$stmt->close();
$conn->close();
?>
