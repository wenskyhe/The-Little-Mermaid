<?php
use PHPUnit\Framework\TestCase;

// Include the file that contains the function to be tested
include_once "..\The-Little-Mermaid\COSC - 310 - Little Mermaids - Aquademia\AquaDemia\Code\PHP\fcn_assignmentLis.php";

class assignmentLisTest extends TestCase
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
        $assignmentsData = getAssignmentsData($this->pdo, $CourseID, $UserID);

        // Assertions
        $this->assertIsArray($assignmentsData);
        // Add more specific assertions based on your expected output
    }

    protected function tearDown(): void
    {
        // Close the database connection after testing
        $this->pdo = null;
    }
}