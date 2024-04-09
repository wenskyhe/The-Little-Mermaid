<?php
include '../Assets/PHP/assignmentFunctions.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
</head>
<body>
    <h1>Create Your Quiz</h1>
    <form action="" method="POST">
        <label for="numberOfQuestions">Number of Questions:</label>
        <input type="number" id="numberOfQuestions" name="numberOfQuestions" min="1" required>
        <button type="submit">Generate Questions</button>
    </form>

    <?php if ($numberOfQuestions > 0): ?>
    <form action="../Assets/PHP/assignmentFunctions.php" method="POST">
        <?php echo $assignmentManager->generateQuizQuestions($numberOfQuestions); ?>
        <button type="submit">Submit Quiz</button>
    </form>
    <?php endif; ?>
</body>
</html>
