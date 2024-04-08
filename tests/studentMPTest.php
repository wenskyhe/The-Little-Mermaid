<?php

use PHPUnit\Framework\TestCase;

require_once "..\The-Little-Mermaid\COSC - 310 - Little Mermaids - Aquademia\AquaDemia\Code\Pages\studentMP.php";


class studentMPTest extends TestCase
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

    public function testFetchEnrolledCourses()
    {
        $courseIDs = fetchEnrolledCourses($this->pdo, 1); // Assuming UserID 1
        $this->assertIsArray($courseIDs);
        // Add more assertions as needed
    }

    public function testFetchCourseDetails()
    {
        $courseDetails = fetchCourseDetails($this->pdo, 1); // Assuming CourseID 1
        $this->assertIsArray($courseDetails);
        $this->assertArrayHasKey('courseName', $courseDetails);
        $this->assertArrayHasKey('AverageGrade', $courseDetails);
        // Add more assertions as needed
    }

    public function testFetchAverageGrade()
    {
        $averageGrade = fetchAverageGrade($this->pdo, 1, 1); // Assuming CourseID 1 and UserID 1
        $this->assertIsNumeric($averageGrade);
        // Add more assertions as needed
    }

    public function testFetchUpcomingAssignments()
    {
        $courseIDs = [1, 2]; // Assuming CourseIDs 1 and 2
        $upcomingAssignments = fetchUpcomingAssignments($this->pdo, $courseIDs);
        $this->assertIsArray($upcomingAssignments);
        // Add more assertions as needed
    }

    protected function tearDown(): void
    {
        // Close the database connection after testing
        $this->pdo = null;
    }

}
