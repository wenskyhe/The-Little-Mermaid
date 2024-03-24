<?php 

require_once('enrollmentManager.php');

// For the given userID, set the 'Accepted' field to true. The student enrollment is no longer pending

if (isset($_GET['userid'])){
        $userid=$_GET['userid'];
        $courseid=$_GET['courseid'];

        $query = "UPDATE enrollment SET Accepted = 1 WHERE UserId = " . $userid . " AND CourseId = " . $courseid;
        $result = mysqli_query($conn, $query);
        if($result){
            echo "Accepted!";
            header('Location: ../../Pages/acceptStudent.php');
        }
        else{
            die(mysqli_error($conn));
        }
    }
?>