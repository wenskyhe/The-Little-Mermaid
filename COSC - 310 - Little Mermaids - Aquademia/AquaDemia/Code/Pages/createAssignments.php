<?php

use PHPUnit\Event\Test\Prepared;

include '../Assets/PHP/assignmentFunctions.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Management</title>
    <link rel="stylesheet" href="../Assets/CSS/central.css">
    <style>

        .assignmentManagement {
            width: 100%;

        }

        .left, .right {
            padding: 5px;
        }

        .left label {
            display: block;
            margin: 7px 0 5px;
            color: white;
            font-size: medium;
        }

        .left input[type="text"],
        .left input[type="date"],
        .left input[type="number"],
        .left input[type="file"],
        .left select,
        .left textarea {
            width: fit-content;
            padding: 5px;
            margin-bottom: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .left input[type="submit"], .left button {
            background-color: whitesmoke;
            color: black;
            padding: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            justify-content: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Assignment Management</h1>
    </header>

    <div class="assignmentManagement" style="display:flex; overflow:hidden;">
        <div class="left" style="flex: 1;">
        <h2>Create an Assignment</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <!-- Course selection -->
            <label for="course">Select a Course:</label>
            <select name="course" required>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= htmlspecialchars($course['courseID']) ?>"><?= htmlspecialchars($course['courseName']) ?></option>
                <?php endforeach; ?>
            </select>
            <br>

            <!-- Other fields (title, description, dueDate, weight) -->
            <label for="title">Enter Title:</label>
            <input type="text" name="title" placeholder="Title" required>
            <br>

            <label for="description">Enter Description:</label>
            <textarea name="description" placeholder="Description" required></textarea>
            <br>

            <label for="dueDate">Enter Due Date:</label>
            <input type="date" name="dueDate" required>
            <br>

            <label for="weight">Enter Assignment Weight:</label>
            <input type="number" name="weight" step="0.01" required>\
            <br>

            <!-- Type selection -->
            <label for="assignmentType">Select Assignment Type:</label>
            <select name="type" id= "assignmentType">
                <option value="essay">Essay</option>
                <option value="quiz">Quiz</option>
            </select>
            <br>

            <label for="">Visibility Status:</label>
            <select name="visibility" id="visibility">
                <option value="false">Not Visible</option>
                <option value="true">Visible</option>
            </select>
            <br>

            <label for="assignmentFile">Enter file here:</label>
            <input type="file" name="assignmentFile" id="assignmentFile">
            <br>

            <button type="submit">Submit</button>
            <br>
        </form>
        <?php if ($successNotify): ?>
            <div class="success-message"><?= $_SESSION['successNotify'] ?></div>
        <?php endif; ?>
    </div>
    <div class="right" style="flex: 1; overflow:auto; max-height: 85vh;">
        <h2>Edit Assignments</h2>
        <!-- Add form to handle course selection and button click -->
        <form method="POST">
            <label for="courseSelection">Select a Course:</label>
            <select name="courseSelection" required>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= htmlspecialchars($course['courseID']) ?>"><?= htmlspecialchars($course['courseName']) ?></option>
                <?php endforeach; ?>
            </select>
            <!-- Add a hidden input field to identify the action -->
            <input type="hidden" name="action" value="displayAssignments">
            <button type="submit">Display Assignments</button>
        </form>
        <br>
        
        <!-- Place for assignments to be displayed -->
        <?php if (!empty($assignments)): ?>
            <?php foreach ($assignments as $assignment): ?>
                <div class="assignment">
                    <h2><?= htmlspecialchars($assignment['title']) ?></h2>
                    <p>Due Date: <?= htmlspecialchars($assignment['dueDate']) ?></p>
                    <p>Weight: <?= htmlspecialchars($assignment['weight']) ?></p>
                    <!-- Add Edit button/link here if needed -->
                    <button type="submit">Edit</button>
                    <br>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>