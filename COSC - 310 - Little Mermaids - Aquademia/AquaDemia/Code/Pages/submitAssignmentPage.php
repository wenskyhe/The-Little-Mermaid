<?php

//ruby 
session_start();
require_once 'dbh.inc.php';

if (!isset($_SESSION["UserID"])) {
    echo "_SESSION[UserID] is not setted";
    header("Location: ../../Code/");
} else {
    echo "_SESSION[UserID] is " . $_SESSION["UserID"];
    $UserID = $_SESSION["UserID"];
}

if (isset($_GET['assignmentID']) && !empty($_GET['assignmentID'])) {
    $assignmentID = $_GET['assignmentID'];
    $_SESSION['assignmentID'] = $assignmentID;
    echo "'assignmentID' is set to" . " $assignmentID by get ";
} else {
    $assignmentID = $_SESSION['assignmentID'];
    echo "'assignmentID' is set to" . " $assignmentID by SESSION ";
}


// Function to fetch assignment data
function getAssignmentData($pdo, $assignmentID) {
    $sql = "SELECT Description, DueDate, type, title
            FROM Assignments
            WHERE assignmentID = :assignmentID AND visibilityStatus = 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':assignmentID', $assignmentID, PDO::PARAM_STR);
    $stmt->execute(); 
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to fetch submission data
function getSubmissionInfo($pdo, $UserID, $assignmentID) {
    $sql = "SELECT Grade, SubmissionDate, feedback
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
function generateStatus($allowSubmit, $SubmissionInfo) {
    $message = "You have submit the assignment at " . $SubmissionInfo['SubmissionDate'] . ". ";
    if (!$allowSubmit) {
        $message = $message . "Submission is closed. ";}
        if ($SubmissionInfo['Grade'] == -1) {
            $message = $message . "The instructor has not graded your assignment yet.";
        } else {
            $message = $message . "Your grade is " . $SubmissionInfo['Grade'] .".";
        }
    if(!empty($SubmissionInfo['feedback'])){
        $message = $message . " Instructor's words: " . $SubmissionInfo['feedback'];
    }
    
    return $message;
}


// Function to fetch quiz questions and Choices
function getQuizQuestions($pdo, $assignmentID) {
    $quizQuestions = [];

    $sql = "SELECT questionNum, questionText, ChoiceA, ChoiceB, ChoiceC, ChoiceD, correctChoice
            FROM QuizQuestions
            WHERE assignmentID = :assignmentID";

    $stmt = $pdo->prepare($sql);
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

// In $assignmentData = [Description, DueDate, type, title]
$assignmentData = getAssignmentData($pdo, $assignmentID);
//print_r($assignmentData);

// In $SubmissionInfo = [Grade, SubmissionDate]
$SubmissionInfo = getSubmissionInfo($pdo, $UserID, $assignmentID);
//print_r($SubmissionInfo);

$allowSubmit = isSubmissionAllowed($assignmentData['DueDate']);
echo "allowSubmit" . $allowSubmit;

echo "SubmissionInfo: ";
print_r($SubmissionInfo);

if (!isset($SubmissionInfo['SubmissionDate']) ) {
    $submissionStatus = "You have not submitted this assignment. ";
} else{
    $submissionStatus = generateStatus($allowSubmit, $SubmissionInfo);
}

//echo $assignmentData['type'];
$quizQuestions = [];
if ($assignmentData['type']=='quiz' && $allowSubmit) {
    $quizQuestions = getQuizQuestions($pdo, $assignmentID);
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
    <link rel="stylesheet" href="../Assets/CSS/central.css">
    <style>
        body {
        margin-left: 5%;
        margin-right: 5%;
        }
        input[type=submit]  {
        background-color:#40026f; /* Purple */
        border: none;
        color: white;
        padding: 16px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        transition-duration: 0.4s;
        cursor: pointer;
        }
    </style>
</head>
<body>
<p style="text-align: right;">
    <a href="studentView.php"><?php echo $_SESSION["Username"] ?></a><?php echo "  " ?>
    <a href="login.html">Logout</a>
</p>
    <!-- Assignment name, due date, and status -->
    <h1 style='color:#deb9fb ;'><?php echo $assignmentData['title'] ?></h1>
    <h3>Due Date: <?php echo $assignmentData['DueDate'] ?></h3>
    
    <h3><?php echo $submissionStatus ?></h3>
    <h3>Description:</h3>
    <h2><?php echo $assignmentData['Description'] ?></h2>

    <?php if ($assignmentData['type']=='quiz' && $allowSubmit ) { ?>
        <!-- Quiz questions form -->
        <h2 style='color:#deb9fb ;'>Quiz Questions</h2>
        <form id="quizForm" action="..\Assets\PHP\submitQuiz.php" method="post">
        <input type="hidden" name="title" value="<?php echo $assignmentName; ?>">
        <input type="hidden" name="courseID" value="<?php echo $CourseID; ?>">
        <input type="hidden" name="studentID" value="<?php echo $UserID; ?>">

        <?php foreach ($quizQuestions as $question) { ?>
            <div>
                <p><?php echo $question['questionNum'] ?>. <?php echo $question['questionText'] ?></p>
                <label style='color: white;'><input  type="radio" name="answers[<?php echo $question['questionNum'] ?>]" value="ChoiceA"> <?php echo $question['ChoiceA'] ?></label><br>
                <label style='color: white;'><input type="radio" name="answers[<?php echo $question['questionNum'] ?>]" value="ChoiceB"> <?php echo $question['ChoiceB'] ?></label><br>
                <label style='color: white;'><input type="radio" name="answers[<?php echo $question['questionNum'] ?>]" value="ChoiceC"> <?php echo $question['ChoiceC'] ?></label><br>
                <label style='color: white;'><input type="radio" name="answers[<?php echo $question['questionNum'] ?>]" value="ChoiceD"> <?php echo $question['ChoiceD'] ?></label><br>
            </div>
        <?php } ?>
        <br>
                <input type="submit" value="Submit Quiz">
        </form>
    <?php } else if ($allowSubmit) { ?>
         <!-- Essay Upload Here  -->
         <form id="uploadForm" action="..\Assets\PHP\uploadAssignment.php" method="post" enctype="multipart/form-data" onsubmit="uploadFile(event)">

        <label style='color: white;' for="assignmentFile">Upload Assignment File (DOC, DOCX, TXT only):</label>
        <br>
        <br>
        <input type="file" name="assignmentFile" id="assignmentFile" accept=".doc, .docx, .txt">
        <input type="submit" value="Submit">

        </form>
    <?php } ?>

    <!-- Display notification if any -->
    <?php
if (isset($_SESSION['upload_message'])) { ?>
    <p><?php echo $_SESSION['upload_message']; ?></p>
    <?php
    unset($_SESSION['upload_message']); // Clear the session variable
}
?>
</body>
</html>
