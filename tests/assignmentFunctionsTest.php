<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use DBConnection;
use AssignmentManager;
use mysqli;
use mysqli_stmt;



include("..\The-Little-Mermaid\COSC - 310 - Little Mermaids - Aquademia\AquaDemia\Code\Assets\PHP\assignmentFunctions.php");


class AssignmentFunctionsTest extends TestCase {
    private $dbConnMock;
    private $assignmentManager;


    // Function to test Database Connection
    protected function setUp(): void {
        // Create the example mysqli class
        $this->dbConnMock = $this->createMock(mysqli::class);
        $this->assignmentManager = new AssignmentManager($this->dbConnMock);
    }


    // This is the function to Test fetch courses
    public function testFetchCourse() {
        // The expected result set
        $expectedCourse = [
            ['courseID' => 1, 'courseName' => 'Course 1'],
            ['courseID' => 2, 'courseName' => 'course 2']
        ];

        // Mocking the prepare and execute behavior

        $stmtMock = $this->createMock(mysqli_stmt::class);
        $this->dbConnMock->method('prepare')->willReturn($stmtMock);
        $stmtMock->method('execute');

        // The behavior for getting the result return mock of mysqli result
        $resultMock = $this->createMock(mysqli_result::class);
        $stmtMock->method('get_result')->willReturn($resultMock);

        // Configure to return the right data
        $resultMock->expects($this->any())->method('fetch_assoc')->willReturn($expectedCourse[0], $expectedCourse[1], null);

        // Call the method under test
        $courses = $this->assignmentManager->fetchCourse(1);

        // Assert the fetched courses match expected results
        $this->assertEquals($expectedCourse, $courses);
    }


    // This function will test the createAssignments function
    public function testCreateAssignment(){
        $stmtMock = $this->createConfiguredMock(mysqli_stmt::class,[
            'execute' => true,
            'close' => null,
        ]);

        $this->dbConnMock->method('prepare')->willReturn($stmtMock);

        $result = $this->assignmentManager->createAssignment(1, "Title", "Description", "2023-01-01", 10, "essay", "visible", null);
        $this->assertTrue($result);
    }


    // This is function to test the fetch Assignments Method
    public function testFetchAssignments() {
        
        // Mock the mysqli_result object to simulate database rows being fetched.
        $resultMock = $this->createMock(mysqli_result::class);
        $resultMock->method('fetch_assoc')->willReturnOnConsecutiveCalls(['assignmentID' => 1, 'title' => 'Assignment 1', 'dueDate' => '2023-01-01', 'weight' => '10'], null //Simulate the end of the result set 
        );

        $stmtMock = $this->createMock(mysqli_stmt::class);
        $stmtMock->method('get_result')->willReturn($resultMock);
        $this->dbConnMock->method('prepare')->willReturn($stmtMock);

        $assignments = $this->assignmentManager->fetchAssignments(1);

        //Assert 
        $this->assertCount(1, $assignments);
        $this->assertEquals('Assignment 1', $assignments[0]['title']);

    }

    // This is a function to test the Generate Quiz Questions
    /**
     * @dataProvider questionsDataProvider
     */
    #[DataProvider('questionsDataProvider')]
    public function testGenerateQuizQuestions($numberOfQuestions, $expectedOccurrences) {
        $htmlOutput = $this->assignmentManager->generateQuizQuestions($numberOfQuestions);

        // Checking if the output has the correct number of question divs
        $this->assertSame($expectedOccurrences, substr_count($htmlOutput, "<div class='question'>"));

        // Checking to make sure all question parts are there for each question
        for ($i = 1; $i <= $numberOfQuestions; $i++) {
            $this->assertStringContainsString("Question $i", $htmlOutput);
            $this->assertStringContainsString("name= 'questions[$i][text]'", $htmlOutput);

            foreach(['A', 'B', 'C', 'D'] as $option) {
                $this->assertStringContainsString("name+'questions[$i][options][$option]'", $htmlOutput);
            }
            $this->assertStringContainsString("name='question[$i][answer]'", $htmlOutput);
        }
    }

    public static function questionsDataProvider() {
        return [
            [1, 1], // Testing for 1 question
            [2, 2], // 2 Questions
            [5, 5] // 5 Questions
        ];
    }

    
    // This will test the getAssignmentId function
    public function testGetAssignmentID() {
        // Setup
        $courseID = 1;
        $title = 'Sample Title';
        $description = 'Sample Description';
        $dueDate = '2023-01-01';
        $weight = 10;
        $type = 'quiz';
        $visibilityStatus = 'visible';
        $assignmentFile = 'path/to/file';
    
        // Mock the mysqli_stmt and mysqli_result objects
        $stmtMock = $this->createMock(mysqli_stmt::class);
        $resultMock = $this->createMock(mysqli_result::class);
    
        // Configure the database connection mock to return the statement mock
        $this->dbConnMock->method('prepare')->willReturn($stmtMock);
    
        // Simulate successful execution of the statement
        $stmtMock->method('execute')->willReturn(true);
    
        // Simulate fetching a result set that includes an assignment ID
        $resultMock->method('get_result')->willReturn($resultMock);
        $resultMock->method('fetch_assoc')->willReturn(['assignmentID' => 123]);
    
        // Assume that the prepare method is called twice: once for insert, once for select
        $this->dbConnMock->expects($this->exactly(2))->method('prepare')->willReturn($stmtMock);
        
        // Call the method under test
        $assignmentID = $this->assignmentManager->getAssignmentID($courseID, $title, $description, $dueDate, $weight, $type, $visibilityStatus, $assignmentFile);
    
        // Verify that the method returns an integer assignment ID
        $this->assertIsInt($assignmentID);
        // Optionally, check if the assignment ID matches the expected value
        $this->assertEquals(123, $assignmentID);
    }
    
    
    // This will test the submit Quiz function
    public function testSubmitQuiz() {
        $questions = [
            ['text' => 'What is 2+2?', 'options' => ['A' => '3', 'B' => '4', 'C' => '5', 'D' => '6'], 'answer' => 'B']
        ];
        $assignmentID = 123;
        $courseID = 1;
    
        $stmtMock = $this->createConfiguredMock(mysqli_stmt::class, [
            'execute' => true,
        ]);
        $this->dbConnMock->method('prepare')->willReturn($stmtMock);
    
        $result = $this->assignmentManager->submitQuiz($questions, $assignmentID, $courseID);
    
        $this->assertTrue($result);
    }
    
}
?>