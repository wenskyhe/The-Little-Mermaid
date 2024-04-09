<?php  
final class newDetails{

    //connection insulating method, *an obligitory method* 
    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    //Method to update user details
    public function changeDetails($conn,$firstName,$lastName,$email,$phoneNumber,$password,$oldPassword,$confirmPassword,$userID){
        $userName = $firstName."_".$lastName;
        
        
        if(empty($password)){
            $password = $_SESSION["Password"]; //checks if no new password has been provided, verifies session password is unaltered if so
        }
        else if($oldPassword != $_SESSION["Password"] || $confirmPassword != $password){ 
            return "Error: enter correct password details";  //if old password is incorrect OR Confirm New Password is incorrect, returns error string 
            //redirects to update page
        }
        $sql = "UPDATE users SET firstName = ?, lastName = ?, Email = ?, phoneNumber = ?, passwordHash = ?, userName = ? 
        WHERE users.userID = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("sssssss", $firstName, $lastName, $email, $phoneNumber, $password,$userName,$userID);
        
        if ($stmt->execute()) { 
            $_SESSION["Username"] = $userName;
            $stmt->close();
            $conn->close();       
            return "Details updated"; //returns pass string, redirects to update page
           } else {
            $stmt->close();
            $conn->close();     
             return "Error: Failed to update details"; //returns error string if there is an update error, redirects to update page
           }
           
    
    }
    
    }
    session_start();
$servername = "localhost";
$username = "root"; // default XAMPP MySQL username
$password = ""; // default XAMPP MySQL password is empty
$dbname = "aquademia";

$userID = $_SESSION["userID"];


$conn = new mysqli($servername, $username, $password, $dbname); //will end if connection fails
    if ($conn->connect_error) {
        die("Connection Failed". $conn->connect_error);
    }


    
$_SESSION["Password"] = $uPassword;

//retreives input from editDetails.php html form
$firstName = $conn -> real_escape_string($_POST["fname"]);
$lastName = $conn -> real_escape_string($_POST["lname"]);
$email = $conn -> real_escape_string($_POST["email"]);
$phoneNumber = $conn -> real_escape_string($_POST["phoneNumber"]);

$password = $conn -> real_escape_string($_POST["password"]);
$oldpassword = $conn -> real_escape_string($_POST["oldPassword"]);
$confirmPassword = $conn -> real_escape_string($_POST["confirmPassword"]);



$detailAlteration =new newDetails($conn); //class instantiation (needed for testing at a minimum, but it is good practice)
$detailAlteration->changeDetails($conn,$firstName,$lastName,$email,$phoneNumber,$password,$oldpassword,$confirmPassword,$userID); //detail update method
header("Location: ../../Pages/editDetails.php"); //refreshing update page 
$_SESSION["Password"] = [""]; //empty password from the session for security 

?>