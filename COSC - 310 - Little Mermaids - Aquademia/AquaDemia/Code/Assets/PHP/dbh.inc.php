<?php
$dsn = "mysql:host=localhost;dbname=myfirstdatabse";
$dbusername = "root";
$dbpasswword = "";

try{
    $pdo = new PDO($dsn, $dbosername. $dbpasswword);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
} catch ( PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
