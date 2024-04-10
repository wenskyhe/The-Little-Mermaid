<?php

namespace editDetailsManager;
use PHPUnit\Framework\TestCase;
use newDetails;
use mysqli;
include("../The-Little-Mermaid/COSC - 310 - Little Mermaids - Aquademia/AquaDemia/Code/Assets/PHP/editDetailsManager.php");

//uses userID 12 as the test user in teh default schema

final class editDetailsManagerTest extends TestCase{

//not a test, merley a function to retreive user info
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
    $userID = 12; //userID = 12 is the test user
    //grabs test user data
        $stmt = $conn->prepare("SELECT userID, firstName, lastName, phoneNumber, email, passwordHash, userName FROM users WHERE users.userID= ?");
            $stmt->bind_param("s",$userID);
            if($stmt->execute()){
                $stmt->bind_result($ActualUserID, $ActualFirstName, $ActualLastName, $ActualPhoneNumber, $ActualEmail, $ActualPasswordHash, $userName);

                
    // Fetch rows and store them in the array
    if ($stmt->fetch()) {
        
        //stores test user data in $user array
        $user = array( 
            'userID' => $ActualUserID,
            'firstName' => $ActualFirstName,
            'lastName' => $ActualLastName,
            'phoneNumber' => $ActualPhoneNumber,
            'email' => $ActualEmail,
            'userName' => $userName,
            'passwordHash' => $ActualPasswordHash
        );
        return $user;
        } else{
            printf("fetch failed"); //prints to output if data is not present after query
        }
        
       
    
    }
    else{
        printf("stmt excecution failed"); //prints to output if stmt fails
    }
    $conn->close();
    $stmt->close();
}
//verfies no details will be altered if input boxes remain empty

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
//new values in update
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
    $user = $this->checkUserInfo(); //loads $user array with the User values 

    //comparing array values instead of comparing the equivilence of the array for debugging purposes
    $this->assertEquals($firstName,$user['firstName']); //comparing sql query of first name vs excpected
    $this->assertEquals($lastName,$user['lastName']); //comparing sql query of last name vs excpected
    $this->assertEquals($email,$user['email']);//comparing sql query of email vs excpected
    $this->assertEquals($phoneNumber,$user['phoneNumber']);//comparing sql query of phone number vs excpected
    $this->assertEquals($Upassword,$user['passwordHash']);//comparing sql query of password vs excpected
    $this->assertEquals($firstName."_".$lastName,$user["userName"]); //verfies username got updated via the firstname and lastname updates
    $this->assertEquals($userID,$user['userID']);// verifying the primary key userID is identical verifying the sae objects were compared

    $this->testNoChanges(); //can use the test to reset the dummy user to default values
    $this->resetTestObject();

session_destroy();
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
    $_SESSION["Password"] = "password"; //makes sure session details remain constant across tests
    $_SESSION["UserID"]  = 12; //makes sure session details remain constant across tests
    //default values
    $firstName = "firstname";
    $lastName = "lastname";
    $email = "test@gmail.com";
    $phoneNumber = "12508630738";
    $password = "password";
    $oldpassword = "password";
    $confirmPassword = "password";
    $userID = $_SESSION["UserID"];
    //
    //class object creation
    $detailAlteration =new newDetails($conn);
    //method to be tested
    $resutlt = $detailAlteration->changeDetails($conn,$firstName,$lastName,$email,$phoneNumber,$password,$oldpassword,$confirmPassword,$userID);
    $this->assertEquals("Details updated", $resutlt); //verifies method return of no errors, only makes a return if the statement goes through without erros
    session_destroy();

}

public function testIncorrectConfirmPassword(){ //Tests that when user enters "confirm new password" incorrectly, no updates go through

    session_start();

    $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "aquademia";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }
    $_SESSION["Password"] = "password"; //makes sure session details remain constant across tests
    $_SESSION["UserID"]  = 12; //makes sure session details remain constant across tests
    //default values
    $firstName = "firstname";
    $lastName = "lastname";
    $email = "test@gmail.com";
    $phoneNumber = "12508630738";
    $password = "password";
    $oldpassword = "password";
    $confirmPassword = "WrongPassword"; //User enters incorrect "confirm  password"
    $userID = $_SESSION["UserID"];
    //
    //class object creation
    $detailAlteration =new newDetails($conn);
    //method to be tested
    $resutlt = $detailAlteration->changeDetails($conn,$firstName,$lastName,$email,$phoneNumber,$password,$oldpassword,$confirmPassword,$userID);
    $this->assertEquals("Error: enter correct password details", $resutlt); //verifies the failed password info message is returned
    $user = $this->checkUserInfo();
    
    session_destroy();


}
//METHOD USED TO RESET OBJECT TO DEFAULT TESTING VALUES/ATRIBUTES
public function resetTestObject(){
 session_start();

    $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "aquademia";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }
    $_SESSION["Password"] = "password"; //makes sure session details remain constant across tests
    $_SESSION["UserID"]  = 12; //makes sure session details remain constant across tests
    //default values
    $firstName = "firstname";
    $lastName = "lastname";
    $email = "test@gmail.com";
    $phoneNumber = "12508630738";
    $password = "password";
    $oldpassword = "password";
    $confirmPassword = "password";
    $userID = $_SESSION["UserID"];
    $detailAlteration =new newDetails($conn);
    $result = $detailAlteration->changeDetails($conn,$firstName,$lastName,$email,$phoneNumber,$password,$oldpassword,$confirmPassword,$userID);
    $this->assertEquals($result, "Details updated");
    session_destroy();
}
}