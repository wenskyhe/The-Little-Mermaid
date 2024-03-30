<?php
require_once 'config.php';
require_once 'dbh.inc.php';

// Assuming you have received the quiz answers and other necessary data
$assignmentName =  $_SESSION['assignmentName'];
$courseID = 2;
$studentID = 1;
$answers = $_POST['answers']; // Array containing user's answers for each question
//print_r($answers );
// Count the total number of questions and correct answers
$totalQuestions = count($answers);
$correctAnswers = 0;

// Iterate through the answers array and check correctness
foreach ($answers as $questionNum => $userAnswer) {
    // Fetch the correct choice from the database based on $questionNum
    $sql = "SELECT correctChoice
            FROM QuizQuestions
            WHERE CourseID = :courseID
            AND AssignmentName = :assignmentName
            AND questionNum = :questionNum";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':courseID', $courseID, PDO::PARAM_INT);
    $stmt->bindParam(':assignmentName', $assignmentName, PDO::PARAM_STR);
    $stmt->bindParam(':questionNum', $questionNum, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['correctChoice'] === $userAnswer) {
        // Increment correctAnswers count if the user's answer is correct
        $correctAnswers++;
    }
}

// Calculate the grade
$grade = intval(($correctAnswers / $totalQuestions) * 100);
echo "grade" . $grade;
// Update the grade in the Assignments table
$sql = "UPDATE Assignments
        SET Grade = :grade
        WHERE AssignmentName = :assignmentName
        AND CourseID = :courseID
        AND StudentID = :studentID";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':grade', $grade, PDO::PARAM_INT);
$stmt->bindParam(':assignmentName', $assignmentName, PDO::PARAM_STR);
$stmt->bindParam(':courseID', $courseID, PDO::PARAM_INT);
$stmt->bindParam(':studentID', $studentID, PDO::PARAM_INT);

if ($stmt->execute()) {
    // Grade updated successfully
    $_SESSION['upload_message'] = "Grade updated successfully.";
} else {
    // Error updating grade
    $_SESSION['upload_message'] = "Error updating grade.";
}

// Close the database connection
$pdo = null;

header("refresh:0.001;url=submitAssignmentPage.php");
?>
