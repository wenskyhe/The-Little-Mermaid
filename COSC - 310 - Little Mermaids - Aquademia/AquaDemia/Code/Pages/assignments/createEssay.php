<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an essay</title>
    <link rel="stylesheet" href="../../Assets/CSS/central.css">
</head>
<body>
    <h2>Enter essay details </h2>
<form action="../../Assets/PHP/createEssayManager.php" method="post">
        <input type="number" placeholder="Course ID"  id="courseID" name="courseID" required><br>
        <input type="text" placeholder="Title"  id="assignmentTitle" name="assignmentTitle" required><br>
        <textarea placeholder= "Description" id="assignmentDescription" name="assignmentDescription" required></textarea><br>
        <!-- Label -->
        <div style="padding-top: 1%; padding-bottom: 1%">
        <a for="birthday" >Due date: </a>
        <input type="datetime-local" id="deadline" name="deadline" required><br>
        </div>
        <input type="number" placeholder="Total Marks"  id="assignmentMarks" name="assignmentMarks" required><br>
        <button class="button button1" style="align-self: center;">Create essay</button> 
    </div>
    </form>
    <button onclick="location.href = '../createAssignments.php';" type="button" class="button button1">Back</button>
</body>
</html>