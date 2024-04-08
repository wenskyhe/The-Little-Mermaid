<?php
namespace assignmentListFcn; // Namespace declaration for the test file
use PHPUnit\Framework\TestCase;
use PDO;

// Include the file that contains the function to be tested
require_once("../The-Little-Mermaid/COSC - 310 - Little Mermaids - Aquademia/AquaDemia/Code/Assets/PHP/assignmentListFcn.php");

class assignmentListTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $servername = "localhost";
        $username = "root"; // default XAMPP MySQL username
        $password = ""; // default XAMPP MySQL password is empty
        $dbname = "aquademia";
        // Establish a connection to the database for testing
        $this->pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    }

    public function testGetAssignmentsData()
    {
        // Call the function with test data
        $CourseID = 1; // Assuming CourseID 1 for testing
        $UserID = 1; // Assuming UserID 1 for testing
        $result = getAssignmentsData($this->pdo, $CourseID, $UserID);

        // Assertions
        $this->assertIsArray($result);

        // Call the function with test data
        $CourseID = 2; // Assuming CourseID 1 for testing
        $UserID = 1; // Assuming UserID 1 for testing
        $result = getAssignmentsData($this->pdo, $CourseID, $UserID);
        
        // Assertions
        $this->assertIsArray($result);
    }

    protected function tearDown(): void
    {
        // Close the database connection after testing
        $this->pdo = null;
    }
}
