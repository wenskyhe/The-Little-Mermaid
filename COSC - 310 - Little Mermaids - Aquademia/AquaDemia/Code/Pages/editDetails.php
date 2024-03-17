<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aquademia";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection Failed". $conn->connect_error);
}

$sql = "SELECT Username, FirstName, LastName, PhoneNumber, Email, PasswordHash FROM Users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $firstname = $row["FirstName"];
        $lastname = $row["LastName"];
        $phonenumber = $row["PhoneNumber"];
        $email = $row["Email"];
        $password = $row["PasswordHash"];
        

    //   echo "id: " . $row["Username"]. "<br> - Name: " . $row["FirstName"]. " " . $row["LastName"]. "<br>";
    }
  } else {
    echo "0 results";
  }
  $conn->close();
  ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Details</title>
    <link rel="stylesheet" href="../Assets/CSS/central.css">
</head>
<body style="padding-left: 10%;">
    <h1 style="padding-top: 5%;">Edit your details</h1>

    <form action="../Assets/PHP/editDetailsManager.php" method="post">
        <input type="text" placeholder="<?= $firstname?>"  id="fname" name="fname"><br>
        <input type="text" placeholder="<?= $lastname?>" id="lname" name="lname"><br>
        <input type="text" placeholder="<?= $email?>" id="email" name="email"><br>
        <input type="text" placeholder="<?= $phonenumber?>" id="phoneNumber" name="fname"><br>
    <!-- Once we get the database working, the placeholder will be the user's previous details     -->
        <input type="password" placeholder="Enter your old password" id="password" name="oldPassword"><br>
        <input type="password" placeholder="Create a password" id="password" name="password"><br>  
        <input type="password" placeholder="Confirm your password" id="confirmPassword" name="confirmPassword"><br>
    </form> 

    <button class="button button1" style="margin-top: 5%;">Save changes</button> 
</body>
</html>