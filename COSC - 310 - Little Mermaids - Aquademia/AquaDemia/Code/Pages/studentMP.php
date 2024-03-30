<?php 

include_once 'config.php';
include_once 'dbh.inc.php';

$UserID = 1;
// Fetch enrolled courses for the student
$sql = "SELECT CourseID
        FROM studentRegistration 
        WHERE StudentID = :UserID"; 

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':UserID', $UserID, PDO::PARAM_INT);
$stmt->execute();
$courseIDs = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Initialize an empty associative array to store course details
$courseDetails = array();

// Loop over each CourseID in $courseIDs
foreach ($courseIDs as $courseID) {
    $sql = "SELECT CourseID, Subject, CourseNumber
            FROM courses
            WHERE CourseID = :CourseID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':CourseID', $courseID, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Store course details in the associative array using CourseID as the key
    $courseDetails[$courseID] = array(
        'Subject' => $row['Subject'],
        'CourseNumber' => $row['CourseNumber'],
        'AverageGrade' => null // Initialize AverageGrade
    );

    // Retrieve assignments and grades for the course
    $sql = "SELECT A.AssignmentName, A.Grade, C.Weight
            FROM Assignments A
            INNER JOIN CourseAssignments C ON A.CourseID = C.CourseID AND A.AssignmentName = C.AssignmentName
            WHERE A.CourseID = :CourseID
            AND A.StudentID = :UserID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':CourseID', $courseID, PDO::PARAM_INT);
    $stmt->bindParam(':UserID', $UserID, PDO::PARAM_INT);
    $stmt->execute();

    // Initialize variables to calculate total grade, total weight, and count the number of assignments
    $totalGrade = 0;
    $totalWeight = 0;

    // Fetch data for assignments and calculate total grade and total weight
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $grade = $row['Grade'];
        $weight = $row['Weight'];

        // Ignore assignments with grade -1 (not graded)
        if ($grade >= 0) {
            $totalGrade += $grade * $weight;
            $totalWeight += $weight;
        }
    }

    // Calculate average grade for the course
    $averageGrade = $totalWeight > 0 ? round(($totalGrade / $totalWeight) * 100, 2) : 0;

    // Update the AverageGrade in courseDetails array
    $courseDetails[$courseID]['AverageGrade'] =( $averageGrade/100);
}

//print_r($courseDetails);
// Initialize an empty array to store upcoming assignments
$upcomingAssignments = array();

// Loop over each CourseID in $courseIDs
foreach ($courseDetails as $courseID => $course) {
  // Retrieve assignments for the course where the due date is greater than the current time
  $sql_1 = "SELECT AssignmentName, DueDate
          FROM CourseAssignments
          WHERE CourseID = :CourseID
          AND DueDate > NOW()";

  $stmt = $pdo->prepare($sql_1);
  $stmt->bindParam(':CourseID', $courseID, PDO::PARAM_INT);
  $stmt->execute();
  
  // Fetch data for upcoming assignments in this course
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $assignmentName = $row['AssignmentName'];
      $dueDate = $row['DueDate'];

      // Add the upcoming assignment to the array
      $upcomingAssignments[] = array(
          'CourseID' => $courseID,
          'AssignmentName' => $assignmentName,
          'DueDate' => $dueDate
      );
  }
}

$pdo = null;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Main Page</title>
    <style> 
        .row {
          display: flex;
          flex-wrap: wrap;
          padding: 0 4px;
        }

        /* Create four equal columns that sits next to each other */
        .column {
          flex: 25%;
          max-width: 25%;
          padding: 0 4px;
        }

        .column img {
          margin-top: 25px;
          vertical-align: middle;
          width: 100%;
        }
        
        .caption {
            text-align: left;
            margin-top: 8px;
        }

        /* Responsive layout - makes a two column-layout instead of four columns */
        @media screen and (max-width: 800px) {
          .column {
            flex: 50%;
            max-width: 50%;
          }
        }

        /* Responsive layout - makes the two columns stack on top of each other instead of next to each other */
        @media screen and (max-width: 600px) {
          .column {
            flex: 100%;
            max-width: 100%;
          }

        }
        
        img.hover-shadow {
          transition: 0.3s;
        }

        .hover-shadow:hover {
          box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }


    </style>
    
</head>
<body>

<h3> Enrolled Courses </h3>


<div class="row">
    <?php 
    //print_r($courseDetails);
    foreach ($courseDetails as $courseID => $course) { ?>
        <div class="column">
            <a href="assignmentsLis.php?CourseID=<?php echo urlencode($courseID); ?>">
                <img src="../Assets/Images/defaultPic.jpg" class="hover-shadow">
                <div class="caption" >
                    <?php echo $course['Subject'] . ' ' . $course['CourseNumber']; ?>
                    </a><?php echo "Current Grade: " . $course['AverageGrade']; ?>
                  </div>
             <br>
            
        </div>
    <?php } ?>
</div>

<br>
<br>
<h3> Upcoming Assignments </h3>
<!--contain assignments such that 1. due date>now 2. no submission records-->
<ul>
  <?php
  foreach ($upcomingAssignments as $assignment) {
    $assignmentLink = "submitAssignmentPage.php?assignmentName=" . urlencode($assignmentName) . "&dueDate=" . urlencode($assignment['DueDate']);
    echo "<li><a href='$assignmentLink'>{$assignment['AssignmentName']}</a> - Due Date: {$assignment['DueDate']}</li>";
}
  ?>
</ul>
</div>
</body>
