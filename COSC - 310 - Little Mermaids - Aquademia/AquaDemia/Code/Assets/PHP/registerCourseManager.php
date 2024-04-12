<?php

    final class courseRegistration{
        private $conn;

        public function __construct(mysqli $conn) {
            $this->conn = $conn;
        }

        public function findID($courseName){
            // $query = "SELECT CourseID FROM Courses WHERE CourseName = ? LIMIT 1";
            // $stmt = $this -> conn -> execute_query($query, [$courseName]);
            // $result = $stmt->get_result();
            // return $result['courseID'];

            $stmt = $this->conn->prepare('SELECT courseID FROM Courses WHERE CourseName = ? LIMIT 1');
            $stmt->bind_param('s', $courseName);
            $stmt->execute();
            $result = $stmt->get_result();
            $value = mysqli_fetch_array($result);
            return $value['courseID'];
            
        }

        public function alreadyEnrolled($userID, $courseID){
            $query = "SELECT EnrollmentID FROM Enrollment WHERE UserID = ? AND CourseID = ? LIMIT 1";
            $result = $this -> conn -> execute_query($query, [$userID, $courseID]);
            if($result->num_rows == 1) {
                //found
                return true;
            }
            return false;
        }

        public function courseExists($courseID){
            $query = "SELECT CourseID FROM Courses WHERE CourseID = ? LIMIT 1";
            $result = $this -> conn -> execute_query($query, [$courseID]);
            if($result->num_rows == 1) {
            //found
                return true;
            }
            return false;
        }

            //function that takes in a courseID and userID and adds a pending enrollment to the enrollment table.
        public function registerCourse($userID, $courseID, $connection){
            if($this-> alreadyEnrolled($userID, $courseID)){
                // echo '<script>
                // alert("That enrollment already exists!");
                // </script>';
            return "Already enrolled."; 
            }

            if(!$this->courseExists($courseID)){
                // echo '<script>
                // alert("That course doesnt exist!");
                // window.location.href="../../Pages/registerCourse.html";
                // </script>';
                return "Course does not exist.";
            }

            $stmt = $connection -> prepare("INSERT INTO Enrollment (UserID, CourseID, EnrollmentDate, Accepted) VALUES (?,?,CURDATE(),'0')");
            $stmt ->bind_param("ss",$userID, $courseID);
        
            if ($stmt->execute()) {
                // echo '<script>
                // alert("Registration is now pending");
                // window.location.href="../../Pages/registerCourse.html";
                // </script>';
                return "Enrollment created.";
            } else {
             echo "Error: ". $stmt->error;
            }
        
            $stmt->close();
        }
    }

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

    $courseName = $conn -> real_escape_string($_POST["courseName"]);
    $userID = $_SESSION["UserID"];

    $registration = new courseRegistration($conn);
    $courseID = $registration-> findID($courseName);
    // $result = $registration->execute_query("SELECT CourseID FROM courses WHERE CourseID = ? LIMIT 1", [$courseID]);
    $result = $registration->registerCourse($userID, $courseID, $conn);
    
    if($result == "Enrollment created.") {
        // found
        echo '<script>
        alert("Registration is now pending");
        </script>';
    }
    elseif($result == "Already enrolled."){
        echo '<script>
            alert("You are already enrolled in that course!");
            </script>';
    }
    elseif($result == "Course does not exist."){
    echo '<script>
        alert("The course you are trying to enroll in does not exist!");
        </script>';
    }
    echo '<script>window.location.href="../../Pages/registerCourse.html";
    </script>';

    $conn->close();

?>