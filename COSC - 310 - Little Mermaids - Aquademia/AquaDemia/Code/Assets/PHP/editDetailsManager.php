```php
<?php  
final class newDetails{
    private $conn;

    // Connection insulating method, an obligatory method
    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // Method to update user details
    public function changeDetails($conn, $firstName, $lastName, $email, $phoneNumber, $password, $oldPassword, $confirmPassword, $userID){
        $userName = $firstName . "_" . $lastName;
        
        try {
            if(empty($password)){
                $password = $_SESSION["Password"]; // Checks if no new password has been provided, uses session password if so
            } else if($oldPassword != $_SESSION["Password"] || $confirmPassword != $password){ 
                return "Error: enter correct password details"; // Error for incorrect password details
            }

            $sql = "UPDATE users SET firstName = ?, lastName = ?, Email = ?, phoneNumber = ?, passwordHash = ?, userName = ? 
            WHERE users.userID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $firstName, $lastName, $email, $phoneNumber, $password, $userName, $userID);
            
            if ($stmt->execute()) { 
                $_SESSION["Username"] = $userName;
                return "Details updated"; // Success message
            } else {
                return "Error: Failed to update details"; // Generic error message
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Check for duplicate entry error code
                // Identify which field caused the duplicate entry
                if (strpos($e->getMessage(), 'Email') !== false) {
                    echo '<script>
                    alert("That email is already in use!");
                    </script>';
                    return "Error: This email is already in use.";
                } else if (strpos($e->getMessage(), 'phoneNumber') !== false) {
                    echo '<script>
                    alert("That phone number is already in use!");
                    </script>';
                    return "Error: This phone number is already in use.";
                }
            } else {
                // For other SQL errors
                return "An error occurred: " . $e->getMessage();
            }
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $conn->close();
        }
    }
}
// Your existing session_start() and connection logic here...
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


    

//retreives input from editDetails.php html form
$firstName = $conn -> real_escape_string($_POST["fname"]);
$lastName = $conn -> real_escape_string($_POST["lname"]);
$email = $conn -> real_escape_string($_POST["email"]);
$phoneNumber = $conn -> real_escape_string($_POST["phoneNumber"]);

$password = $conn -> real_escape_string($_POST["password"]);
$oldpassword = $conn -> real_escape_string($_POST["oldPassword"]);
$confirmPassword = $conn -> real_escape_string($_POST["confirmPassword"]);





$detailAlteration = new newDetails($conn);
$message = $detailAlteration->changeDetails($conn, $firstName, $lastName, $email, $phoneNumber, $password, $oldPassword, $confirmPassword, $userID);

//header("Location: ../../Pages/editDetails.php"); // Refreshing update page
echo '<script>window.location.href="../../Pages/editDetails.php"</script>';
$_SESSION["Password"] = ""; // Clear password from the session for security

?>
```


