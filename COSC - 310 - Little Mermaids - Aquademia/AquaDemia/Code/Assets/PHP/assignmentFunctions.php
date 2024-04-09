<?php

use PHPUnit\Event\Test\Prepared;

session_start();

// Class for connecting to the database
class DBConnection {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            self::$conn = new mysqli("localhost", "root", "", "aquademia");
            if (self::$conn->connect_error) {
                die("Connection failed: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }
}

// Class contain functions to manage assignments
class AssignmentManager {
    private $dbConn;

    // This is where you connect to the DB
    public function __construct(mysqli $dbConn){
        $this->dbConn = $dbConn;
    }

    // This is where the courses related to the professor is fetched so assignments can be picked
    public function fetchCourse($professorID) {
        $stmt = $this->dbConn->prepare("SELECT courseID, courseName FROM courses WHERE professorID = ?");
        $stmt->bind_param("i", $professorID);
        $stmt->execute();
        $result = $stmt->get_result();
        $courses = [];
        while ($row = $result->fetch_assoc()){
            $courses[] = $row;
        }
        $stmt->close();
        return $courses;

    }
    // This is where assignments are created
    public function createAssignment($courseID, $title, $description, $dueDate, $weight, $type, $visibilityStatus, $assignmentFile) {
        if ($type == 'quiz') { // If it is type quiz then let the user be sent here
            // Save information to session!
            $_SESSION['assignmentDetails'] = [
                'courseID' => $courseID,
                'title' => $title,
                'dueDate' => $dueDate,
                'type' => $type
            ];

            // Create quiz before sending to quiz creation
            $stmt = $this->dbConn->prepare("INSERT INTO assignments (courseID, title, description, dueDate, weight, type, visibilityStatus, assignmentFilePath) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssisss", $courseID, $title, $description, $dueDate, $weight, $type, $visibilityStatus, $assignmentFile);
            if ($stmt->execute()) {
                $success = true;
                $_SESSION['assignmentDetails'] = [
                    'courseID' => $courseID,
                    'title' => $title,
                    'dueDate' => $dueDate,
                    'type' => $type
                ];
                // Lead to quiz
                header("Location: createQuiz.php");
            } else {
                $success = false;
                header("Location: professorView.php");
            }
            $stmt->close();

        } else if ($type == 'essay'){ // If its not type quiz then let the quiz submit normally!
    
            $stmt = $this->dbConn->prepare("INSERT INTO assignments (courseID, title, description, dueDate, weight, type, visibilityStatus, assignmentFilePath) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssisss", $courseID, $title, $description, $dueDate, $weight, $type, $visibilityStatus, $assignmentFile);
            if ($stmt->execute()) {
                $success = true;
                header("Location: createAssignments.php");
            } else {
                $success = false;
                header("Location: professorView.php");
            }
            $stmt->close();
        }
        return $success;
    }

    public function fetchAssignments($courseID){
        $stmt = $this->dbConn->prepare("SELECT assignmentID, title, dueDate, weight FROM Assignments WHERE courseID = ?");
        $stmt->bind_param("i", $courseID);
        $stmt->execute();
        $result = $stmt->get_result();
        $assignments = [];

        while ($row = $result->fetch_assoc()) {
            $assignments[] = $row;
        }
        $stmt->close();
        return $assignments;
    }

    public function generateQuizQuestions($numberOfQuestions) {
        $htmlOutput = '';
        for ($i = 1; $i <= $numberOfQuestions; $i++) {
            $htmlOutput .= "<div class='question'>";
            $htmlOutput .= "<h3>Question $i</h3>";
            $htmlOutput .= "<label for='question$i'>Enter Question:</label>";
            $htmlOutput .= "<input type='text' id='question$i' name='questions[$i][text]' required><br>";
            
            foreach (['A', 'B', 'C', 'D'] as $option) {
                $htmlOutput .= "<label for='option{$i}_$option'>$option:</label>";
                $htmlOutput .= "<input type='text' id='option{$i}_$option' name='questions[$i][options][$option]' required><br>";
            }
            
            $htmlOutput .= "<label for='answer$i'>Correct Answer:</label>";
            $htmlOutput .= "<select id='answer$i' name='questions[$i][answer]' required>";
            foreach (['A', 'B', 'C', 'D'] as $option) {
                $htmlOutput .= "<option value='$option'>$option</option>";
            }
            $htmlOutput .= "</select><br>";
            $htmlOutput .= "</div>";
        }
        return $htmlOutput;
    }
    
    public function getAssignmentID($title, $dueDate, $type) {

        $stmt = $this->dbConn->prepare("SELECT assignmentID FROM assignments WHERE title = ? AND  dueDate = ? AND  type = ?");
        $stmt->bind_param("sss", $title, $dueDate, $type);
        $stmt->execute();
        $result = $stmt->get_result();
        $assignmentID  = null;   
        if ($result->num_rows == 1) {
            // Gets the one row for assignmentID
            $row = $result->fetch_assoc();
            $assignmentID = $row['assignmentID'];
            $stmt->close();
            return $assignmentID;
        } else if ($result->num_rows > 2){
            // There are duplicate assignments
            echo "There are multiple assignments with the same Name";
            $assignmentID = "Multiple Assignment of the same value";
            return $assignmentID;
        } else {
            // No assignment found
            echo "There is no file found";
            $assignmentID = "No assignment found";
            return $assignmentID;
        }
        
    }

    public function submitQuiz($questions, $assignmentID, $courseID) {

        foreach ($questions as $questionNum => $questionData) {

            $questionText = $questionData['text'];
            // Correctly retrieve choice options
            $choiceA = $questionData['options']['A'];
            $choiceB = $questionData['options']['B'];
            $choiceC = $questionData['options']['C'];
            $choiceD = $questionData['options']['D'];
            $correctChoice = "Choice" . $questionData['answer'];

            // The statement and binding parameters
            $stmt = $this->dbConn->prepare("INSERT INTO QuizQuestions (courseID, assignmentID, QuestionNum, QuestionText, ChoiceA, ChoiceB, ChoiceC, ChoiceD, CorrectChoice) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiissssss", $courseID, $assignmentID, $questionNum, $questionText, $choiceA, $choiceB, $choiceC, $choiceD, $correctChoice);
            $stmt->execute();
            
        }

        $success = true;
        return $success;
    }
    
}


$professorID = 10; //Example professorID
$dbConnection = DBConnection::getConnection();
$assignmentManager = new AssignmentManager($dbConnection);

$courses = $assignmentManager->fetchCourse($professorID);
$selectedCourse = $course[0]['courseID'] ?? 0; // Default to the first course if available

$successNotify = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'displayAssignments') {

    // Set selected course based on user input
    $selectedCourse = $_POST['courseSelection'];
    // Fetch the assignments for the selected course
    $assignments = $assignmentManager->fetchAssignments($selectedCourse);
} else {
    // What does it default to?
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action']) && $_POST['action'] == 'create') {

    $courseID = $_POST['course'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $dueDate = $_POST['dueDate'];
    $weight = $_POST['weight'];
    $type = $_POST['type'];
    $visibility = $_POST['visibility'];
    $assignmentFile = $_POST['assignmentFile'];

    if($assignmentManager->createAssignment($courseID, $title, $description, $dueDate, $weight, $type, $visibility, $assignmentFile)) {
        
    } else {
        $successNotify = "Failed to create an assignment.";
    }
}

// Number of Questions for Quiz Post
if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['numberOfQuestions'])) {
    $numberOfQuestions = (int)$_POST['numberOfQuestions'];
} else {
    $numberOfQuestions = 0;
}


// Submit the quiz to the DataBase
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['questions'])) {

    $questions = $_POST['questions'];
    // Collect Info from session
    $assignmentInfo = $_SESSION['assignmentDetails'];
    $courseID = $assignmentInfo['courseID'];
    $title = $assignmentInfo['title'];
    $dueDate = $assignmentInfo['dueDate'];
    $type = $assignmentInfo['type'];
    unset($_SESSION['assignmentDetails']);

    $assignmentID = $assignmentManager->getAssignmentID($title, $dueDate, $type);

    if ($assignmentManager->submitQuiz($questions, $assignmentID, $courseID)) {
        header("Location: ../../Pages/createAssignments.php");
        exit();
    } else {
        echo "Failed to submit quiz.";
    }
}
?>