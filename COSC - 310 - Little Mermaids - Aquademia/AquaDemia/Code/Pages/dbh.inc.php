<?php

$servername = "localhost";
$username = "root"; // default XAMPP MySQL username
$password = ""; // default XAMPP MySQL password is empty
$dbname = "aquademia";

try{
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch ( PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
