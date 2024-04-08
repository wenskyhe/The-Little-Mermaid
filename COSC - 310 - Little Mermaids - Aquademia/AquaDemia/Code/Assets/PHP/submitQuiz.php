<?php
//ruby
session_start();
require_once 'dbh.inc.php';

// Function to calculate the grade based on user's answers
// Function to calculate the grade based on user's answers
function calculateGrade($pdo, $assignmentID, $courseID, $answers) {
    $totalQuestions = count($answers);
    $correctAnswers = 0;

    foreach ($answers as $questionNum => $userAnswer) {
        $sql = "SELECT correctChoice
                FROM QuizQuestions
                WHERE CourseID = :courseID
                AND assignmentID = :assignmentID
                AND questionNum = :questionNum";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':courseID', $courseID, PDO::PARAM_INT);
        $stmt->bindParam(':assignmentID', $assignmentID, PDO::PARAM_INT);
        $stmt->bindParam(':questionNum', $questionNum, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo "correct: " . $row['correctChoice'] . "/ ";
            echo "input: " . $userAnswer . "/ ";

            if ($row['correctChoice'] == $userAnswer) {
                $correctAnswers++;
            }
        } else {
            echo "Error fetching data for question " . $questionNum . "<br>";
        }
    }

    echo "Correct Answers: " . $correctAnswers . "<br>";

    $grade = intval(($correctAnswers / $totalQuestions) * 100);
    return $grade;
}

// Function to update the grade in the database
function updateGrade($pdo, $assignmentID, $courseID, $userID, $grade) {
    // First, check if there is an existing submission for this assignment and user
    $sqlCheckSubmission = "SELECT submissionID FROM Submissions WHERE assignmentID = :assignmentID AND userID = :userID";
    $stmtCheckSubmission = $pdo->prepare($sqlCheckSubmission);
    $stmtCheckSubmission->bindParam(':assignmentID', $assignmentID, PDO::PARAM_INT);
    $stmtCheckSubmission->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmtCheckSubmission->execute();

    $submissionID = null;
    if ($row = $stmtCheckSubmission->fetch(PDO::FETCH_ASSOC)) {
        $submissionID = $row['submissionID'];
    }

    // Update or insert grade based on whether a submission exists
    if ($submissionID !== null) {
        // Submission exists, update the grade
        $sqlUpdateGrade = "UPDATE Submissions SET grade = :grade WHERE submissionID = :submissionID";
        $stmtUpdateGrade = $pdo->prepare($sqlUpdateGrade);
        $stmtUpdateGrade->bindParam(':grade', $grade, PDO::PARAM_INT);
        $stmtUpdateGrade->bindParam(':submissionID', $submissionID, PDO::PARAM_INT);
        return $stmtUpdateGrade->execute();
    } else {
        // No submission exists, insert a new record
        $sqlInsertGrade = "INSERT INTO Submissions (assignmentID, userID, submissionDate, grade) 
                           VALUES (:assignmentID, :userID, NOW(), :grade)";
        $stmtInsertGrade = $pdo->prepare($sqlInsertGrade);
        $stmtInsertGrade->bindParam(':assignmentID', $assignmentID, PDO::PARAM_INT);
        $stmtInsertGrade->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmtInsertGrade->bindParam(':grade', $grade, PDO::PARAM_INT);
        return $stmtInsertGrade->execute();
    }
}

// Assuming you have received the necessary data
$assignmentID = $_SESSION['assignmentID'];
$courseID = 2;
$userID = 1;
$answers = $_POST['answers']; // Array containing user's answers for each question
//print_r($answers);
// Calculate the grade
$grade = calculateGrade($pdo, $assignmentID, $courseID, $answers);
echo $grade;
// Update or insert the grade in the database
if (updateGrade($pdo, $assignmentID, $courseID, $userID, $grade)) {
    $_SESSION['upload_message'] = "Grade updated successfully.";
} else {
    $_SESSION['upload_message'] = "Error updating grade.";
}

// Close the database connection
$pdo = null;

header("refresh:10;url=fcn_submitAssignmentPage.php");
?>
