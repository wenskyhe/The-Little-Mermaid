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

$stmt = $conn -> prepare("UPDATE Users SET FirstName = ?, LastName = ?, Email = ?,  ")

?>