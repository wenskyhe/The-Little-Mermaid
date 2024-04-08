<?php  
final class newDetails{
    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }
    public function changeDetails($conn,$firstName,$lastName,$email,$phoneNumber,$password,$oldPassword,$confirmPassword,$userID){
        $userName = $firstName."_".$lastName;
        if(empty($password)){
            $password = $_SESSION["Password"];
        }
        else if($oldPassword != $_SESSION["Password"] || $confirmPassword != $password){ 
            return "Error: enter correct password details";
        }
        $sql = "UPDATE users SET firstName = ?, lastName = ?, Email = ?, phoneNumber = ?, passwordHash = ?, userName = ?
        WHERE users.userID = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param("sssssss", $firstName, $lastName, $email, $phoneNumber, $password,$userName,$userID);
        
        if ($stmt->execute()) { 
            $_SESSION["Username"] = $userName;
            $stmt->close();
            $conn->close();       
            return "Details updated";
           } else {
            $stmt->close();
            $conn->close();     
             return "Error: Failed to update details";
           }
           
    
    }
    
    }
    session_start();
$servername = "localhost";
$username = "root"; // default XAMPP MySQL username
$password = ""; // default XAMPP MySQL password is empty
$dbname = "aquademia";

$userID = $_SESSION["userID"];


$conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection Failed". $conn->connect_error);
    }


    
$_SESSION["Password"] = $uPassword;

$firstName = $conn -> real_escape_string($_POST["fname"]);
$lastName = $conn -> real_escape_string($_POST["lname"]);
$email = $conn -> real_escape_string($_POST["email"]);
$phoneNumber = $conn -> real_escape_string($_POST["phoneNumber"]);

$password = $conn -> real_escape_string($_POST["password"]);
$oldpassword = $conn -> real_escape_string($_POST["oldPassword"]);
$confirmPassword = $conn -> real_escape_string($_POST["confirmPassword"]);



$detailAlteration =new newDetails($conn);
$detailAlteration->changeDetails($conn,$firstName,$lastName,$email,$phoneNumber,$password,$oldpassword,$confirmPassword,$userID);
header("Location: ../../Pages/editDetails.php");
$_SESSION["Password"] = [""]; //empty password from the session for security 

?>