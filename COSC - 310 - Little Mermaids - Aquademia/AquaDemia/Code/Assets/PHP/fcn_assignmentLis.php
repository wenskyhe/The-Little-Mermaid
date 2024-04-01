<?php


function getAssignmentsData($pdo, $CourseID, $UserID) {
    $sql_1 = "SELECT AssignmentID, Title, DueDate
            FROM Assignments
            WHERE CourseID = $CourseID AND visibilityStatus = 1";

    $sql_2 = "SELECT AssignmentID, Grade, submissionDate
              FROM Submissions
              WHERE UserID = '$UserID'";

    $assignmentsData = [];

    $stmt = $pdo->query($sql_1);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $assignmentID = $row['AssignmentID'];
        $dueDate = $row['DueDate'];
        $isCurrent = strtotime($dueDate) > time();

        $assignmentsData[$assignmentID] = [
            'assignmentName' => $row['Title'],
            'DueDate' => $dueDate,
            'Grade' => -1, // Initialize Grade as -1
            'SubmissionTime' => null, // Initialize SubmissionTime as null
            'IsCurrent' => $isCurrent // Store whether the assignment is current or past
        ];
    }

    $stmt = $pdo->query($sql_2);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $assignmentID = $row['AssignmentID'];
        if (array_key_exists($assignmentID, $assignmentsData)) {
            $assignmentsData[$assignmentID]['Grade'] = $row['Grade'];
            $assignmentsData[$assignmentID]['SubmissionTime'] = $row['submissionDate'];
        }
    }

    return $assignmentsData;
}
?>
