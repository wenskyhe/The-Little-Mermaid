<?php

//ruby 
include_once '\AquaDemia\Code\Assets\PHP\config.php';
include_once '\AquaDemia\Code\Assets\PHP\dbh.inc.php';

// Function to fetch assignment data
function getAssignmentData($pdo, $CourseID, $assignmentID) {
    $sql = "SELECT Description, DueDate, type, title
            FROM Assignments
            WHERE CourseID = :CourseID AND assignmentID = :assignmentID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':CourseID', $CourseID, PDO::PARAM_INT);
    $stmt->bindParam(':assignmentID', $assignmentID, PDO::PARAM_STR);
    $stmt->execute(); 
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to fetch submission data
function getSubmissionData($pdo, $UserID, $assignmentID) {
    $sql = "SELECT Grade, SubmissionDate
            FROM Submissions
            WHERE UserID = :UserID AND assignmentID = :assignmentID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':UserID', $UserID, PDO::PARAM_INT);
    $stmt->bindParam(':assignmentID', $assignmentID, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to check if submission is allowed based on due date
function isSubmissionAllowed($dueDate) {
    return strtotime($dueDate) >= time();
}

// Function to generate submission and grading status
function generateStatus($allowSubmit, $SubmissionDate, $grade) {
    if (!$allowSubmit) {
        return "You have passed the due date. Submission is closed.";
    } else if ($SubmissionDate === null) {
        return "You have not submitted this assignment.";
    } else if ($grade === -1) {
        return "The instructor has not graded your assignment yet.";
    } else {
        return "Your grade is $grade.";
    }
}

// Function to fetch quiz questions and Choices
function getQuizQuestions($pdo, $CourseID, $assignmentID) {
    $quizQuestions = [];

    $sql = "SELECT questionNum, questionText, ChoiceA, ChoiceB, ChoiceC, ChoiceD, correctChoice
            FROM QuizQuestions
            WHERE CourseID = :CourseID AND assignmentID = :assignmentID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':CourseID', $CourseID, PDO::PARAM_INT);
    $stmt->bindParam(':assignmentID', $assignmentID, PDO::PARAM_STR);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $quizQuestions[] = [
            'questionNum' => $row['questionNum'],
            'questionText' => $row['questionText'],
            'ChoiceA' => $row['ChoiceA'],
            'ChoiceB' => $row['ChoiceB'],
            'ChoiceC' => $row['ChoiceC'],
            'ChoiceD' => $row['ChoiceD'],
            'correctChoice' => $row['correctChoice']
        ];
    }

    return $quizQuestions;
}


// Main code starts here
$UserID = 1;
$CourseID = 2;

if (!isset($_SESSION['assignmentID'])) {
    $assignmentID = $_GET['assignmentID'];
    $_SESSION['assignmentID'] = $assignmentID;
} else {
    $assignmentID = $_SESSION['assignmentID'];
}
echo $assignmentID;

$assignmentData = getAssignmentData($pdo, $CourseID, $assignmentID);
$submissionData = getSubmissionData($pdo, $UserID, $assignmentID);

$allowSubmit = isSubmissionAllowed($assignmentData['DueDate']);
$submissionStatus = generateStatus($allowSubmit, $submissionData['SubmissionDate'], $submissionData['Grade']);
//echo $assignmentData['type'];
$quizQuestions = [];
if ($assignmentData['type']=='quiz' && $allowSubmit) {
    $quizQuestions = getQuizQuestions($pdo, $CourseID, $assignmentID);
}
// Close the database connection
$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>submitAssignmentPage</title>
</head>
<body>
    <!-- Assignment name, due date, and status -->
    <h1><?php echo $assignmentData['title'] ?></h1>
    <h3>Due Date: <?php echo $assignmentData['DueDate'] ?></h3>
    
    <h3><?php echo $submissionStatus ?></h3>
    <h3>Description:</h3>
    <h2><?php echo $assignmentData['Description'] ?></h2>

    <?php if ($assignmentData['type']=='quiz' && $allowSubmit ) { ?>
        <!-- Quiz questions form -->
        <h2>Quiz Questions</h2>
        <form id="quizForm" action="Aquademia\AquaDemia\Code\Assets\PHP\submitQuiz.php" method="post">
        <input type="hidden" name="title" value="<?php echo $assignmentName; ?>">
        <input type="hidden" name="courseID" value="<?php echo $CourseID; ?>">
        <input type="hidden" name="studentID" value="<?php echo $UserID; ?>">

        <?php foreach ($quizQuestions as $question) { ?>
            <div>
                <p><?php echo $question['questionNum'] ?>. <?php echo $question['questionText'] ?></p>
                <label><input type="radio" name="answers[<?php echo $question['questionNum'] ?>]" value="ChoiceA"> <?php echo $question['ChoiceA'] ?></label><br>
                <label><input type="radio" name="answers[<?php echo $question['questionNum'] ?>]" value="ChoiceB"> <?php echo $question['ChoiceB'] ?></label><br>
                <label><input type="radio" name="answers[<?php echo $question['questionNum'] ?>]" value="ChoiceC"> <?php echo $question['ChoiceC'] ?></label><br>
                <label><input type="radio" name="answers[<?php echo $question['questionNum'] ?>]" value="ChoiceD"> <?php echo $question['ChoiceD'] ?></label><br>
            </div>
        <?php } ?>
        <br>
        <input type="submit" value="Submit Quiz">
        </form>
    <?php } else if ($allowSubmit) { ?>
         <!-- Essay Upload Here  -->
         <form id="uploadForm" action="Aquademia\AquaDemia\Code\Assets\PHP\uploadAssignment.php" method="post" enctype="multipart/form-data" onsubmit="uploadFile(event)">
        <label for="assignmentFile">Upload Assignment File (DOC, DOCX, TXT only):</label>
        <input type="file" name="assignmentFile" id="assignmentFile" accept=".doc, .docx, .txt">
        <input type="submit" value="Submit">
        </form>
        <div id="notification"></div>
    <?php } ?>

    <!-- Display notification if any -->
    <?php
    if (isset($_SESSION['upload_message'])) {
        echo $_SESSION['upload_message'];
        unset($_SESSION['upload_message']); // Clear the session variable
       
    }
    unset($_SESSION['assignmentID']);
    ?>
</body>
</html>
