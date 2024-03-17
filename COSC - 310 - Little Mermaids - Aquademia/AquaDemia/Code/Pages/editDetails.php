<?php

session_start();
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
        $user_password = $row["PasswordHash"];
        $_SESSION["user_password"] = $user_password;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="padding-left: 10%;">
    <h1 style="padding-top: 5%;">Edit your details</h1>

    <form action="../Assets/PHP/editDetailsManager.php" method="post" id="editDetailsForm">
    <a id = "fnameLabel"><?= $firstname?></a>    
        <input type="text" value="<?= $firstname?>"  id="fname" name="fname" hidden><input type="checkbox" id="editFname"><br>
    <a id = "lnameLabel"><?= $lastname?></a>
        <input type="text" value="<?= $lastname?>" id="lname" name="lname" hidden><input type="checkbox" id="editLname"><br>
    <a id = "emailLabel"><?= $email?></a>
        <input type="text" value="<?= $email?>" id="email" name="email" hidden><input type="checkbox" id="editEmail"><br>
    <a id = "pnumLabel"><?= $phonenumber?></a>
        <input type="text" value="<?= $phonenumber?>" id="phoneNumber" name="phoneNumber" hidden><input type="checkbox" id="editPnum"><br>
    <!-- Once we get the database working, the placeholder will be the user's previous details     -->
        <input type="password" placeholder="Enter your old password" id="password" name="oldPassword"><br>
        <input type="password" placeholder="Create a password" id="password" name="password"><br>  
        <input type="password" placeholder="Confirm your password" id="confirmPassword" name="confirmPassword"><br>
    <button class="button button1" style="margin-top: 5%;" id="buttonSaveChanges">Save changes</button>
</form> 
    <button class="button button1" id="buttonGoBack" onclick="goBack()">Back</button>

    <script>
        // Get references to form elements
        const editFname = document.getElementById('editFname');
        const editLname = document.getElementById('editLname');
        const editEmail = document.getElementById('editEmail');
        const editPnum = document.getElementById('editPnum');
        const fname = document.getElementById('fname');
        const lname = document.getElementById('lname');
        const email = document.getElementById('email');
        const pnum = document.getElementById('phoneNumber');

        editFname.addEventListener('change', function() {
            fname.hidden = !this.checked;
            fnameLabel.hidden = this.checked;});
        editLname.addEventListener('change', function() {
            lname.hidden = !this.checked;
            lnameLabel.hidden = this.checked;});
        editEmail.addEventListener('change', function() {
            email.hidden = !this.checked;
            emailLabel.hidden = this.checked;});
        editPnum.addEventListener('change', function() {
            pnum.hidden = !this.checked;
            pnumLabel.hidden = this.checked;});

        function goBack() {
            window.location.href = 'adminView.php';
        }
    </script>

</body>
</html>