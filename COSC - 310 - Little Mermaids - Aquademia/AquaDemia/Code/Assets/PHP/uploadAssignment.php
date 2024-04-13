
<?php
//ruby

session_start();
require_once 'dbh.inc.php';

// $assignmentID and $UserID are obtained from session
if (!isset($_SESSION["UserID"])) {
    echo "_SESSION[UserID] is not setted";
    header("Location: ../../Code/");
} else {
    echo "_SESSION[UserID] is " . $_SESSION["UserID"];
    $UserID = $_SESSION["UserID"];
}


if (!isset($_SESSION['assignmentID'])) {
    echo $_GET['assignmentID'];
    $assignmentID = $_GET['assignmentID'];
    $_SESSION['assignmentID'] = $assignmentID;
    echo "assignmentID" . $assignmentID;
} else {
    $assignmentID = $_SESSION['assignmentID'];
    echo "_SESSION[assignmentID] is " . $_SESSION["assignmentID"];
}

unset($_SESSION['upload_message']);


// Function to handle file upload and insertion into the database
    function uploadAssignmentFile($pdo, $assignmentID, $userID)
    {
        if (isset($_FILES['assignmentFile']) && $_FILES['assignmentFile']['error'] === 0) {
            $allowedExtensions = array('doc', 'docx', 'txt'); // Allowed file extensions
            $fileExtension = strtolower(pathinfo($_FILES['assignmentFile']['name'], PATHINFO_EXTENSION));
                
            if (in_array($fileExtension, $allowedExtensions)) {
                $fileName = $_FILES['assignmentFile']['name'];
                $uploadDir = '../UPLOADS/'; // Directory to store uploaded files

                // Check if the destination directory exists, if not, create it
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true); // Create the directory with full permissions
                }

                    // Check if the assignment already exists for the student
                $stmtCheck = $pdo->prepare("SELECT COUNT(*) AS count FROM Submissions WHERE assignmentID = ? AND userID = ?");
                $stmtCheck->execute([$assignmentID, $userID]);
                $rowCount = $stmtCheck->fetch(PDO::FETCH_ASSOC)['count'];

                if ($rowCount > 0) {
                    // Assignment already exists, update the submissionFilePath
                    $stmtUpdate = $pdo->prepare("UPDATE Submissions 
                                                SET submissionFilePath = ?, submissionDate = NOW(), grade = -1
                                                WHERE assignmentID = ? AND userID = ?");
                    $stmtUpdate->execute([$fileName, $assignmentID, $userID]);
                } else {
                    // Insert the file into the database
                    $stmtInsert = $pdo->prepare("INSERT INTO Submissions (assignmentID, userID, submissionFilePath, grade) VALUES (?, ?, ?, -1)");
                    $stmtInsert->execute([$assignmentID, $userID, $fileName]);
                }
    
                $destPath = $uploadDir . $fileName;
                echo $destPath;
    
            // Move the uploaded file to the destination directory
            if (move_uploaded_file($_FILES['assignmentFile']['tmp_name'], $destPath)) {
                $_SESSION['upload_message'] = "File uploaded successfully.";
            } else {
                $_SESSION['upload_message'] = "Error uploading file.";
            }
            $_SESSION['assignmentID'] = $assignmentID;
        } else {
            $_SESSION['upload_message'] = "No file uploaded or an error occurred.";
            $_SESSION['assignmentID'] = $assignmentID;
        }
    
        // Redirect after processing the file upload
        header("Location: ../../../Code/Pages/submitAssignmentPage.php");
        //exit(); // Stop further execution
    }else{
        $_SESSION['upload_message'] = "No file uploaded.";
        header("Location: ../../../Code/Pages/submitAssignmentPage.php");
    }
}


// Verify Form Submission
echo "Form submitted successfully."; // Debug message to verify form submission


// Handle file upload and insertion into the database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    uploadAssignmentFile($pdo, $assignmentID, $UserID);
}

echo 'rlt';
// Close the database connection
$pdo = null;
