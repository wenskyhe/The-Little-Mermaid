<?php
namespace cn_assignmentList;
use PHPUnit\Framework\TestCase;
use PDO;
use retrieveAssignmentData;
// Include the file that contains the function to be tested
include("..\The-Little-Mermaid\COSC - 310 - Little Mermaids - Aquademia\AquaDemia\Code\Assets\PHP\cn_assignmentList.php");
class assignmentListTest extends TestCase
{
    public function testGetAssignmentsData()
    {
        session_save_path();
        session_start(); // Start the session.
        $servername = "localhost";
        $username = "root"; // default XAMPP MySQL username
        $password = ""; // default XAMPP MySQL password is empty
        $dbname = "aquademia";
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
        if ($conn->errorCode()) {
            die("Connection Failed: " . $conn->errorCode());
        }
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Call the function with test data
        $CourseID = 1; // Assuming CourseID 1 for testing
        $UserID = 1; // Assuming UserID 1 for testing
        $assignmentData = new retrieveAssignmentData($conn);
        $result = $assignmentData->getAssignmentsData($conn, $CourseID, $UserID);

        // Assertions
        $this->assertIsArray($result);
        // Add more specific assertions based on your expected output
        $conn = null;
    }
}