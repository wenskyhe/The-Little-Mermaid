<?php
require_once 'config.php';
require_once 'dbh.inc.php';


// just for the test
$UserID = 1;
$CourseID = 2;

if (!isset($_SESSION['assignmentName'])) {
    $assignmentName =  $_GET['assignmentName'];
    $_SESSION['assignmentName'] =  $assignmentName;
}else{
    $assignmentName = $_SESSION['assignmentName'];
}

// $CourseID was stored when user click into the course,
// access created assigenments in this course:
$sql_1 = "SELECT Description, DueDate, Quiz
          FROM CourseAssignments
          WHERE CourseID = $CourseID and AssignmentName = '$assignmentName'";

// Initialize an empty array to store the organized data
$assignmentsData = array();

// Fetch data for SQL query 1
$stmt = $pdo->query($sql_1);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$assignmentsData = array(
    'Description' => $row['Description'],
    'DueDate' => $row['DueDate'],
    'Grade' => -1, // Initialize Grade as -1
    'SubmissionTime' => null, // Initialize SubmissionTime as null
    'FilePath' => null // Store whether the assignment is current or past
);

$allowSubmit = TRUE;
if (strtotime($assignmentsData['DueDate']) < time()){
    $allowSubmit = FALSE;
    //echo "does not allow submission";
};

$isQuiz = $row['Quiz'];
$quizQuestions = array(); // Initialize 2D array for quiz questions and choices

if ($isQuiz) {
    // Fetch quiz questions and choices
    $sql_2 = "SELECT questionNum, questionText, choiceA, choiceB, choiceC, choiceD, correctChoice
              FROM QuizQuestions
              WHERE CourseID = $CourseID and AssignmentName = '$assignmentName'";

    $stmt = $pdo->query($sql_2);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $questionNum = $row['questionNum'];
        $questionText = $row['questionText'];
        $choiceA = $row['choiceA'];
        $choiceB = $row['choiceB'];
        $choiceC = $row['choiceC'];
        $choiceD = $row['choiceD'];
        $correctChoice = $row['correctChoice'];

        // Store question and choices in the 2D array
        $quizQuestions[] = array(
            'questionNum' => $questionNum,
            'questionText' => $questionText,
            'choiceA' => $choiceA,
            'choiceB' => $choiceB,
            'choiceC' => $choiceC,
            'choiceD' => $choiceD,
            'correctChoice' => $correctChoice
        );
    }
}
// $UserID was stored when user has logged in 
// access students' Description on assignments
$sql_3 = "SELECT Grade, SubmissionTime
          FROM Assignments
          WHERE StudentID = $UserID and AssignmentName = '$assignmentName'";

// Fetch data for SQL query 
$stmt = $pdo->query($sql_3);

// Debugging output
//echo "Number of rows fetched: " . $stmt->rowCount() . "<br>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $assignmentsData['Grade'] = $row['Grade'];
        //echo $assignmentsData['Grade'];
        $assignmentsData['SubmissionTime'] = $row['SubmissionTime'];
        //$assignmentsData['FilePath'] = $row['FilePath'];
    
}

//print_r($assignmentsData);
// if CourseAssignment - Quiz is TRUE, store all the questions and their choices into a 2d array
// and print as a form in html

// Close the database connection
$pdo = null;


$submissionStatus = null;
if (!$allowSubmit){
    $submissionStatus = "You have passed the due date. Submission is closed.";
}else if ($assignmentsData['SubmissionTime'] == null) {
    $submissionStatus = "You have not submitted this assignment";
}else if ($assignmentsData['SubmissionTime'] <=  $assignmentsData['DueDate'] ){
    $submissionStatus = "You have submitted this assignment at " . $assignmentsData['SubmissionTime'];
}

$gradingStatus = null;
if ( $assignmentsData['Grade'] == -1 ){
    $gradingStatus = "The instructor has not grade your assignment yet";
}else if ( $assignmentsData['Grade'] >= 0)
    $gradingStatus = "Your grade is " . $assignmentsData['Grade'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>submitAssignemntPage</title>
</head>
<body>
    <!--Assignment name-->
    </h1><?php echo  $assignmentName?><h1>
    </h3><?php echo  "Due Date: " . $assignmentsData['DueDate']  ?><h3>
    </h3><?php echo  $submissionStatus ?><h3>
    </h3><?php echo  $gradingStatus ?><h3>
    </h3> Description: <h3>
    </h2> <?php echo  $assignmentsData['Description'] ?> <h2>

    <?php if ($isQuiz && !empty($quizQuestions) && $allowSubmit) { ?>
        <!-- Quiz questions form -->
        <h2>Quiz Questions</h2>
        <form id="quizForm" action="submitQuiz.php" method="post">
        <input type="hidden" name="assignmentName" value="<?php echo $assignmentName; ?>">
        <input type="hidden" name="courseID" value="<?php echo $courseID; ?>">
        <input type="hidden" name="studentID" value="<?php echo $studentID; ?>">

        <!-- Assuming $quizQuestions is the array containing quiz questions and choices -->
        <?php foreach ($quizQuestions as $question) { ?>
            <div>
                <p><?php echo $question['questionNum'] ?>. <?php echo $question['questionText'] ?></p>
                <label><input type="radio" name="answers[<?php echo $question['questionNum'] ?>]" value="choiceA"> <?php echo $question['choiceA'] ?></label><br>
                <label><input type="radio" name="answers[<?php echo $question['questionNum'] ?>]" value="choiceB"> <?php echo $question['choiceB'] ?></label><br>
                <label><input type="radio" name="answers[<?php echo $question['questionNum'] ?>]" value="choiceC"> <?php echo $question['choiceC'] ?></label><br>
                <label><input type="radio" name="answers[<?php echo $question['questionNum'] ?>]" value="choiceD"> <?php echo $question['choiceD'] ?></label><br>
            </div>
            <?php } ?>
            <br>
            <input type="submit" value="Submit Quiz">
        </form>

    <?php } else if ($allowSubmit){ ?>
        <!-- Essay Upload Here  -->
        <form id="uploadForm" action="uploadAssignment.php" method="post" enctype="multipart/form-data" onsubmit="uploadFile(event)">
        <label for="assignmentFile">Upload Assignment File (DOC, DOCX, TXT only):</label>
        <input type="file" name="assignmentFile" id="assignmentFile" accept=".doc, .docx, .txt">
        <input type="submit" value="Submit">
        </form>
        <div id="notification"></div>
    <?php } ?>

    

    <?php
    if (isset($_SESSION['upload_message'])) {
        echo $_SESSION['upload_message'];
        unset($_SESSION['upload_message']); // Clear the session variable
    }
    ?>

</form>
</body>
</html>  
