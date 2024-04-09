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

<!-- Only show the header if student -->
<?php
$usertype = $_SESSION["UserType"];
if($usertype == "Student"){
    echo '<header>  
        <div class="container">
            <navBar>
              <navBarElements>
              <img src="../Assets/Images/logo.png" class = "logo">
              <li>
                    <a href="studentMP.php">
                    <div>
                        <i class="fa fa-home" style="font-size:36px; color:white;"></i>
                        Home
                    </div>
                    </a>
                </li>
                <li>
                    <a href="registerCourse.html">
                    <div>
                        <i class="fa fa-plus" style="font-size:36px; color:white;"></i>
                        Register
                    </div>
                    </a>
                </li>
                <li>
                    <a href="editDetails.php">
                    <div>
                        <i class="fa fa-user-circle" style="font-size:36px; color:white;"></i>
                        Profile
                    </div>
                    </a>
                </li>
                <li>
                    <a href="login.html">
                    <div>
                        <i class="fa fa-close" style="font-size:36px; color:white;"></i>
                        Logout
                    </div>
                    </a>
                </li>
              </navBarElements>
            </navbar>
        </div>
</header>';
}
?>


<body>

<div style="padding-left: 5%;">
    <h1 style="">Edit your details</h1>

    <form action="../Assets/PHP/editDetailsManager.php" method="post" id="editDetailsForm">
        
    <label id = "fnameLabel" style="padding-right: 20%">First Name</label>   
    <label id = "lnameLabel">Last Name</label><br>
    <input type="text" value="<?= $firstname?>"  id="fname" name="fname" style="margin-right: 6.75%">
    <input type="text" value="<?= $lastname?>" id="lname" name="lname"><br>

    <label id = "emailLabel" style="padding-right: 22.2%; padding-top:2%">Email</label>
    <label id = "pnumLabel">Phone Number</label><br>
    <input type="text" value="<?= $email?>" id="email" name="email" style="margin-right: 6.75%">
    <input type="text" value="<?= $phonenumber?>" id="phoneNumber" name="phoneNumber"><br>

    <label id = "passwordLabel" style="margin-top: 2%;">Password</label><br>
    <input type="password" placeholder="Enter your old password" id="oldPassword" name="oldPassword"><br>
    <input type="password" placeholder="Create a password" id="password" name="password"><br>  
    <input type="password" placeholder="Confirm your password" id="confirmPassword" name="confirmPassword"><br>
    <button class="button button1" style="margin-top: 5%;" id="buttonSaveChanges">Save changes</button>

    <button class="button button1" id="buttonGoBack" onclick="history.back()">Back</button>

</div>


    <!-- <script>
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
    </script> -->

</body>
</html>