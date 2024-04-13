<?php

use PhpParser\Node\Stmt;
use PHPUnit\Event\Test\Prepared;
use SebastianBergmann\Environment\Console;

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
        $stmt->bind_param("s", $professorID);
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

    // Function to get the assignment detail before sending them to 
    public function fetchAssignmentDetails($assignmentID) {
        $stmt = $this->dbConn->prepare("SELECT * FROM assignments WHERE assignmentID = ?");
        $stmt->bind_param("i", $assignmentID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $stmt->close();
            return $row;
        } else {
            $stmt->close();
            return null; // No assignment found with the given ID
        }
    }

    // Function to retrieve all the submissions fromm students
    public function fetchSubmissionsForAssignment($assignmentID) {
        $submissions = [];
        $stmt = $this->dbConn->prepare("SELECT s.submissionID, s.assignmentID, s.userID, s.submissionDate, s.submissionFilePath, s.grade, s.feedback, u.firstName, u.lastName FROM submissions s JOIN users u ON s.userID = u.userID WHERE s.assignmentID = ?");
        $stmt->bind_param("i", $assignmentID);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $submissions[] = $row;
        }
        $stmt->close();
        return $submissions;
    }
    
    // Submit grade and feed back to database.
    public function gradeSubmission($submissionID, $grade, $feedback, $assignmentID) {
    $stmt = $this->dbConn->prepare("UPDATE submissions SET grade = ?, feedback = ? WHERE submissionID = ?");
    $stmt->bind_param("dsi", $grade, $feedback, $submissionID);
    if ($stmt->execute()) {
        header("Location: viewAssignment.php?assignmentID=" . urldecode($assignmentID));
        return true; // Success
    } else {
        header("Location: createAssignments.php");
        return false; // Failure
    }
}
    

    // This is where the teacher will be able to edit an assignment
    public function editAssignment($assignmentID, $title, $description, $dueDate, $weight, $type, $visibility, $assignmentFilePath) {
        $stmt = $this->dbConn->prepare("UPDATE assignments SET title = ?, description = ?, dueDate = ?, weight = ?, type = ?, visibilityStatus = ?, assignmentFilePath = ? WHERE assignmentID = ?");
        $stmt->bind_param("sssdsssi", $title, $description, $dueDate, $weight, $type, $visibility, $assignmentFilePath, $assignmentID);
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: viewAssignment.php?assignmentID=" . urldecode($assignmentID));
            return true; // Success
        } else {
            $stmt->close();
            header("Location: createAssignment.php");
            return false; // Failure
        }
    }
    public function uploadAssignmentDocument($userFileName) {
        if (isset($_FILES[$userFileName])) {
            if ($_FILES[$userFileName]['error'] === UPLOAD_ERR_OK) {
                $allowedExtensions = ['pdf', 'doc', 'docx']; // Specify allowed file types
                $fileExtension = strtolower(pathinfo($_FILES[$userFileName]['name'], PATHINFO_EXTENSION));
                
                if (in_array($fileExtension, $allowedExtensions)) {
                    $uploadDir = 'C:/xampp/htdocs/Aquademia/The-Little-Mermaid/COSC - 310 - Little Mermaids - Aquademia/AquaDemia/Code/Assets/Uploads/Teacher/';  // Ensure this directory exists and is writable
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $fileName = time() . '-' . basename($_FILES[$userFileName]['name']); // Prefixing the filename with a timestamp to prevent name collisions
                    $filePath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES[$userFileName]['tmp_name'], $filePath)) {
                        echo "File uploaded successfully.";
                    } else {
                        echo "Failed to move the uploaded file.";
                    }
                } else {
                    echo "Invalid file extension.";
                }
            } else {
                echo "Error: " . $_FILES[$userFileName]['error'];
            }
        } else {
            echo "File not found in upload request.";
        }
    }

}


$professorID = $_SESSION["UserID"]; // Grab the professor's ID
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'create') {
    // Assuming you've already fetched courses and set $courses somewhere

    $courseID = $_POST['course'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $dueDate = $_POST['dueDate'];
    $weight = $_POST['weight'];
    $type = $_POST['type'];
    $visibility = ($_POST['visibility'] == 'true') ? 1 : 0;
    $fileName = '';

    // Handling file upload
    if (isset($_FILES['assignmentFile']) && $_FILES['assignmentFile']['error'] == UPLOAD_ERR_OK) {
        $assignmentManager->uploadAssignmentDocument('assignmentFile');
        $fileName = basename($_FILES['assignmentFile']['name']); // Get the basename of the uploaded file
    } else {
        // Error handling if file isn't uploaded
        echo "Error uploading file. Error Code: " . $_FILES['assignmentFile']['error'];
    }

    // Proceed to create the assignment with the uploaded file name
    if ($assignmentManager->createAssignment($courseID, $title, $description, $dueDate, $weight, $type, $visibility, $fileName)) {
        
    } else {
        echo "<div class='error-message'>Failed to create an assignment.</div>";
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
$assignmentID = isset($_GET['assignmentID']) ? $_GET['assignmentID'] : null;
$currentAssignmentDetails = null;
if ($assignmentID) {
    $currentAssignmentDetails = $assignmentManager->fetchAssignmentDetails($assignmentID);
    // Fetch submissions for the assignment
$submissions = $assignmentManager->fetchSubmissionsForAssignment($assignmentID);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['grade']) && isset($_POST['feedback']) && isset($_POST['submissionID'])) {
    $grade = floatval($_POST['grade']);
    $feedback = $_POST['feedback'];
    $submissionID = $_POST['submissionID'];

    if ($assignmentManager->gradeSubmission($submissionID, $grade, $feedback, $assignmentID)) {
        echo "<script>alert('Grade and feedback updated successfully.'); window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Failed to update grade and feedback.');</script>";
    }
}
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'editAssignment') {
    // Assuming assignmentID is fetched from the URL or session
    if ($assignmentManager->editAssignment($assignmentID, $_POST['title'], $_POST['description'], $_POST['dueDate'], $_POST['weight'], $_POST['type'], $_POST['visibility'], $_POST['assignmentFilePath'])) {
        echo "<script>alert('Assignment updated successfully.'); window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Failed to update the assignment.');</script>";
    }
}

?>