<?php
    // login.php
    final class userLogin{
        public function __construct(PDO $conn) {
            $this->conn = $conn;
        }
        public function loginAttempt($conn,$uname,$psw){
            $stmt = $conn->prepare("SELECT UserID, PasswordHash, UserType FROM Users WHERE Username = :uname");
            $stmt->bindParam(':uname', $uname);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($user && ($psw == $user['PasswordHash'])) {
                // Password is correct
                $_SESSION["loggedin"] = true;
                $_SESSION["UserID"] = $user['UserID'];
                $_SESSION["Username"] = $uname;
                // Redirect to welcome page
                echo "Welcome back " . $uname . " we are Happy to see you Again!";
        
                if($user['UserType'] == "Admin"){
                    header("Location: ../../Pages/adminView.php");
                }
                else if($user['UserType'] == "Student"){
                    header("Location: ../../Pages/studentView.php");
                }
                else if($user['UserType'] == "Teacher"){
                    header("Location: ../../Pages/teacherView.php");
                }
            } else {
                // Display an error message
                $alertMessage = "The password you entered was incorrect!";
                echo $alertMessage;
                header("Location: ../../Pages/login.html");
            }
        }
    }

    session_start(); // Start the session.
    $servername = "localhost";
    $username = "root"; // default XAMPP MySQL username
    $password = ""; // default XAMPP MySQL password is empty
    $dbname = "aquademia";

    $user;
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    if ($conn->errorCode()) {
        die("Connection Failed: " . $conn->errorCode());
    }
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    $uname = $_POST['uname'];
    $psw = $_POST['psw'];
$newLogin = new userLogin($conn);
$newLogin->loginAttempt($conn,$uname,$psw)

    ?>