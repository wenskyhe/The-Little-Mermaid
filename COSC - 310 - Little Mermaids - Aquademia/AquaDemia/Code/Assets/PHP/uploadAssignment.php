
<?php
//ruby

require_once 'config.php';
require_once 'dbh.inc.php';


// Function to handle file upload and insertion into the database
function uploadAssignmentFile($pdo, $assignmentID, $userID)
{
    /*
    if (isset($_SESSION['assignmentID']) && isset($_SESSION['userID'])) {
    $assignmentID = $_SESSION['assignmentID'];
    $userID = $_SESSION['userID'];
    */
    if (isset($_FILES['assignmentFile']) && $_FILES['assignmentFile']['error'] === 0) {
        $allowedExtensions = array('doc', 'docx', 'txt'); // Allowed file extensions
        $fileExtension = strtolower(pathinfo($_FILES['assignmentFile']['name'], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            $fileName = $_FILES['assignmentFile']['name'];

            // Check if the assignment already exists for the student
            $stmtCheck = $pdo->prepare("SELECT COUNT(*) AS count FROM Submissions WHERE assignmentID = ? AND userID = ?");
            $stmtCheck->execute([$assignmentID, $userID]);
            $rowCount = $stmtCheck->fetch(PDO::FETCH_ASSOC)['count'];

            if ($rowCount > 0) {
                // Assignment already exists, update the submissionFilePath
                $stmtUpdate = $pdo->prepare("UPDATE Submissions SET submissionFilePath = ? WHERE assignmentID = ? AND userID = ?");
                $stmtUpdate->execute([$fileName, $assignmentID, $userID]);
            } else {
                // Insert the file into the database
                $stmtInsert = $pdo->prepare("INSERT INTO Submissions (assignmentID, userID, submissionFilePath) VALUES (?, ?, ?)");
                $stmtInsert->execute([$assignmentID, $userID, $fileName]);
            }

            $uploadDir = 'uploads/'; // Directory to store uploaded files
            $destPath = $uploadDir . $fileName;

            // Move the uploaded file to the destination directory
            if (move_uploaded_file($_FILES['assignmentFile']['tmp_name'], $destPath)) {
                $_SESSION['upload_message'] = "File uploaded successfully.";
            } else {
                $_SESSION['upload_message'] = "Error uploading file.";
            }
        } else {
            $_SESSION['upload_message'] = "Invalid file type. Allowed file types: DOC, DOCX, TXT.";
        }
        
    } else {
        echo "No file uploaded or an error occurred.";
        
    }
    
    header("refresh:0.001;url=submitAssignmentPage.php");
}
/*
}
*/
// $assignmentID and $UserID are obtained from session
$assignmentID = $_SESSION['assignmentID'];
$UserID = 1; 
//$UserID = $_SESSION['UserID']

// Verify Form Submission
echo "Form submitted successfully."; // Debug message to verify form submission


// Handle file upload and insertion into the database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    uploadAssignmentFile($pdo, $assignmentID, $UserID);
}

// Close the database connection
$pdo = null;