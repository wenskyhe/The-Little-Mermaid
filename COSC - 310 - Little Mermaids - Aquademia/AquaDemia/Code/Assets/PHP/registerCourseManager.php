<?php

    session_start();

    $servername = "localhost";
    $username = "root"; // default XAMPP MySQL username
    $password = ""; // default XAMPP MySQL password is empty
    $dbname = "aquademia";


    //create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection Failed". $conn->connect_error);
    }

    $courseID = $conn -> real_escape_string($_POST["courseId"]);
 
    $result = $conn->execute_query("SELECT CourseID FROM courses WHERE CourseID = ? LIMIT 1", [$courseID]);
    if($result->num_rows == 1) {
        // found
        registerCourse($_SESSION["UserID"], $courseID, $conn);
        //PENDING FUNCTION
    }
    else {
        echo '<script>
            alert("That course doesnt exist!");
            window.location.href="../../Pages/registerCourse.html";
            </script>';
    }

    

    $conn->close();


    //function that takes in a courseID and userID and adds a pending enrollment to the enrollment table.
    function registerCourse($userID, $courseID, $connection){
        $stmt = $connection -> prepare("INSERT INTO Enrollment (UserID, CourseID, EnrollmentDate, Accepted) VALUES (?,?,CURDATE(),'0')");
        $stmt ->bind_param("ss",$userID, $courseID);

        if ($stmt->execute()) {
            echo '<script>
            alert("Registration is now pending");
            window.location.href="../../Pages/registerCourse.html";
            </script>';
        } else {
         echo "Error: ". $stmt->error;
        }

        $stmt->close();
    }

?>
