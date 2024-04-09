<?php

namespace editDetailsManager;
use PHPUnit\Framework\TestCase;
use newDetails;
use mysqli;
include("../The-Little-Mermaid/COSC - 310 - Little Mermaids - Aquademia/AquaDemia/Code/Assets/PHP/editDetailsManager.php");

final class editDetailsManagerTest extends TestCase{


    public function checkUserInfo(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "aquademia";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }
    $_SESSION["Password"] = "password";
    $_SESSION["UserID"]  = 12;
    $userID = 12;
        $stmt = $conn->prepare("SELECT userID, firstName, lastName, phoneNumber, email, passwordHash FROM users WHERE users.userID= ?");
            $stmt->bind_param("s",$userID);
            if($stmt->execute()){
                $stmt->bind_result($ActualUserID, $ActualFirstName, $ActualLastName, $ActualPhoneNumber, $ActualEmail, $ActualPasswordHash);

                
    // Fetch rows and store them in the array
    if ($stmt->fetch()) {
        

        $user = array(
            'userID' => $ActualUserID,
            'firstName' => $ActualFirstName,
            'lastName' => $ActualLastName,
            'phoneNumber' => $ActualPhoneNumber,
            'email' => $ActualEmail,
            'passwordHash' => $ActualPasswordHash
        );
        return $user;
        } else{
            printf("fetch failed");
        }
        
       
    
    }
    else{
        printf("stmt excecution failed");
    }
    $conn->close();
    $stmt->close();
}
public function testNoChanges(){
    session_start();

    $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "aquademia";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }
    $_SESSION["Password"] = "password";
    $_SESSION["UserID"]  = 12;
    
    $firstName = "firstname";
    $lastName = "lastname";
    $email = "test@gmail.com";
    $phoneNumber = "12508630738";
    $password = "password";
    $oldpassword = "password";
    $confirmPassword = "password";
    $userID = $_SESSION["UserID"];
    $detailAlteration =new newDetails($conn);
    $resutlt = $detailAlteration->changeDetails($conn,$firstName,$lastName,$email,$phoneNumber,$password,$oldpassword,$confirmPassword,$userID);
    $this->assertEquals("Details updated", $resutlt);
    session_destroy();

}
public function testAllNewDetails(){
    session_start();

    $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "aquademia";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }
    $_SESSION["Password"] = "password";
    $_SESSION["UserID"]  = 12;
    
    
    $userID = $_SESSION["UserID"];

    $firstName = "newFirstName";
    $lastName = "newLastName";
    $email = "newEmail";
    $phoneNumber = "newPhone";
    $Upassword = "newPassword";
    $oldpassword = "password";
    $confirmPassword = "newPassword";

    $detailAlteration =new newDetails($conn);
    $resutlt = $detailAlteration->changeDetails($conn,$firstName,$lastName,$email,$phoneNumber,$Upassword,$oldpassword,$confirmPassword,$userID);
    $this->assertEquals("Details updated", $resutlt);//testing that the update went through
    $user = $this->checkUserInfo();
    $this->assertEquals($firstName,$user['firstName']);
    $this->assertEquals($lastName,$user['lastName']);
    $this->assertEquals($email,$user['email']);
    $this->assertEquals($phoneNumber,$user['phoneNumber']);
    $this->assertEquals($Upassword,$user['passwordHash']);
    $this->assertEquals($userID,$user['userID']);

    $this->testNoChanges(); //can use the test to reset the dummy user to default values
    

session_destroy();
}
}