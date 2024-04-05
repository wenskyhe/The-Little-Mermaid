<?php 

session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aquademia";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Grab the username from the universal global variable "Username"
    $uname = $_SESSION["Username"];
    
    //SQL statement to grab user's existing firstname, lastname, phonenumber, email and password. These are what will be updated
    $stmt = $conn->prepare("SELECT userID, FirstName, LastName, PhoneNumber, Email, PasswordHash FROM Users WHERE Username = :uname");
    $stmt->bindParam(':uname', $uname);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    //Assign these to variables that will be used in the html code down below
    
    $userID = isset($user["userID"]) ? $user["userID"] : ''; // Assigning user ID from database to a variable
    $_SESSION["userID"] = $userID; // Storing user ID in session variable

    $uPassword = isset($user["PasswordHash"]) ? $user["PasswordHash"] : ''; // Assigning user password from database to a variable
    $_SESSION["Password"] = $uPassword; // Storing user password in session variable

    $firstname = isset($user["FirstName"]) ? $user["FirstName"] : ''; // Assigning user's first name from database to a variable
    $lastname = isset($user["LastName"]) ? $user["LastName"] : ''; // Assigning user's last name from database to a variable
    $email = isset($user["Email"]) ? $user["Email"] : ''; // Assigning user's email from database to a variable
    $phonenumber = isset($user["PhoneNumber"]) ? $user["PhoneNumber"] : ''; // Assigning user's phone number from database to a variable
    
}
catch (Exception $e){
    echo "Connection failed: " . $e->getMessage();
}
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
    <!-- Add eye option -->
    <input type="password" placeholder="Enter your old password" id="oldPassword" name="oldPassword"><br>
        <input type="password" placeholder="Create a password" id="password" name="password"><br>  
        <input type="password" placeholder="Confirm your password" id="confirmPassword" name="confirmPassword"><br>
    <button class="button button1" style="margin-top: 5%;" id="buttonSaveChanges">Save changes</button>
    <input type="hidden" name="Back" value="../../Pages/editDetails.php">
</form> 
    <button class="button button1" id="buttonGoBack" onclick="history.back()">Back</button>

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

        //If user clicks on a checkbox, they will have the option to change that field
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

    </script>

</body>
</html>