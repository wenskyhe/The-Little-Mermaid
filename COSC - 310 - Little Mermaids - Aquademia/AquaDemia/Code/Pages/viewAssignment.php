<?php
include '../Assets/PHP/assignmentFunctions.php';


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Assignment and submissions</title>
    <link rel="stylesheet" href="../Assets/CSS/central.css">
    <style>
        .mainContainer { display: flex; }
        .leftContainer, .rightContainer { flex: 1; padding: 20px; }
        .assignment, .submissions, .submission { border: 1px solid #ccc; padding: 10px; margin-bottom: 20px; }
        .max { width: max-content;}

    </style>
</head>
<body>
    <header>
        <h1>Assignments Management Page</h1>
        <br>
        <p><a href="professorView.php">Home</a></p>
    </header>

    <!--This will contain all the information for this page-->
    <div class="mainContainer">

        <!--This will display the assignment and allow the user to change it-->
        <div class="leftContainer">
            <h2>Assignment Details</h2>
            <!--The Assignment will sit here-->
            <div class="assignment">
                <?php if ($currentAssignmentDetails): ?>
                    <form action="viewAssignment.php?assignmentID=<?= htmlspecialchars($assignmentID) ?>" method="POST">
                        <input type="hidden" name="action" value="editAssignment">
                        <h2>Title: <input type="text" name="title" value="<?= htmlspecialchars($currentAssignmentDetails['title']) ?>" required></h2>
                        <div class="descriptionContainer">
                        <label for="description">Description:</label>
                        <br>
                        <textarea name="description" id="description" required><?= htmlspecialchars($currentAssignmentDetails['description']) ?></textarea>
                        </div>
                        <p>Due Date: <input type="date" name="dueDate" value="<?= htmlspecialchars($currentAssignmentDetails['dueDate']) ?>" required></p>
                        <p>Weight: <input type="number" name="weight" value="<?= htmlspecialchars($currentAssignmentDetails['weight']) ?>" step="0.01" required></p>
                        <p>Type: <?= htmlspecialchars($currentAssignmentDetails['type']) ?></p>
                        <p>Visibility Status: <select name="visibility" required>
                                <option value="visible" <?= $currentAssignmentDetails['visibilityStatus'] == 1 ? 'selected' : '' ?>>Visible</option>
                                <option value="not visible" <?= $currentAssignmentDetails['visibilityStatus'] == 0 ? 'selected' : '' ?>>Not Visible</option>
                            </select>
                        </p>
                        <p>Assignment File Path: <input type="text" name="assignmentFilePath" value="<?= htmlspecialchars($currentAssignmentDetails['assignmentFilePath']) ?>" required></p>
                        <button type="submit" onclick="return confirm('Are you sure you want to update this assignment?');">Submit Changes</button>
                    </form>
                <?php else: ?>
                    <h2>Assignment Details not found.</h2>
                <?php endif; ?>
            </div>


            <!--If you need to edit a portion of a quiz-->
            <div class="quizContainer">

            </div>
        </div>
        <!--This will contain a list of all the student submissions associated to that assignment-->
        <div class="rightContainer">
            <h2>Submissions</h2>
            <div class="submissions">
                <?php if (!empty($submissions)): ?>
                    <?php foreach ($submissions as $submission): ?>
                        <div class="submission">
                            <p>Student ID: <?= htmlspecialchars($submission['userID']) ?></p>
                            <p>Student Name: <?= htmlspecialchars($submission['firstName'] . ' ' . $submission['lastName']) ?></p>
                            <p>Submission Date: <?= htmlspecialchars($submission['submissionDate']) ?></p>
                            <p>Submission FilePaths: <?= htmlspecialchars($submission['submissionFilePath']) ?></p>
                            <p>Submission Grade: <?= htmlspecialchars($submission['grade']) ?>/100</p>
                            <p>Submission Feedback: <?= htmlspecialchars($submission['feedback']) ?></p>
                            <p><a href="../Assets/PHP/download.php?filename=<?= urlencode($submission['submissionFilePath']) ?>" target="_blank">View Submission</a></p>
                            <form method="POST">
                                <input type="hidden" name="submissionID" value="<?= $submission['submissionID'] ?>">
                                <label for="grade<?= $submission['submissionID'] ?>">Enter Grade (%):</label>
                                <input type="number" name="grade" id="grade<?= $submission['submissionID'] ?>" step="0.01" required>
                                <br>
                                <label for="feedback<?= $submission['submissionID'] ?>">Enter Feedback:</label>
                                <input type="text" name="feedback" id="feedback<?= $submission['submissionID'] ?>" required>
                                <br>
                                <button type="submit">Grade</button>
                            </form>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <h2>No Submissions found.</h2>
                <?php endif; ?>
            </div>
        </div>

    </div>
</body>
</html>
