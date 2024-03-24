<?php

$servername = "localhost";
$username = "root"; // default XAMPP MySQL username
$password = ""; // default XAMPP MySQL password is empty
$dbname = "aquademia";


$conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection Failed". $conn->connect_error);
    }

$firstName = $conn -> real_escape_string($_POST["fname"]);
$lastName = $conn -> real_escape_string($_POST["lname"]);
$email = $conn -> real_escape_string($_POST["email"]);
$phoneNumber = $conn -> real_escape_string($_POST["phoneNumber"]);
$password = $conn -> real_escape_string($_POST["password"]);

$sql = "UPDATE Users SET FirstName = ?, LastName = ?, Email = ?, PhoneNumber = ?, PasswordHash = ?";
$stmt = $conn -> prepare($sql);
$stmt -> bind_param("sssss", $firstName, $lastName, $email, $phoneNumber, $password);

if ($stmt->execute()) {
    echo "Changes made.";
   } else {
    echo "Error: ". $stmt->error;
   }

   $stmt->close();
   $conn->close();
?>