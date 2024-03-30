<?php 

require_once('../Assets/PHP/enrollmentManager.php');
$query = "select * from enrollment where Accepted = 0";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Student</title>
    <link rel="stylesheet" href="../Assets/CSS/bootstrap.min.css">
    <link rel="stylesheet" href="../Assets/CSS/central.css">
</head>

<!-- We are using bootstrap.min.css for the table design. However, bootstrap has it's own design for a bunch
of other stuff which we will not be using. Therefore, to override the bootstrap design where we don't 
need it, each element has it's own style. -->

<body style="background-color: rgb(31, 31, 31);">
    <h1 style="text-align: center; color: white; font-family: Arial, Helvetica, sans-serif; padding-top: 5%;">Accept students into courses</h1>
    
    <!-- Table to display all students with pending enrollment -->

    <div class = "container">
        <div class="row">
            <div class="col">
                <div class="card mt-5">
                    <div class = "card-header">
                    <h2 class="text-center text-dark"> Students with pending enrollment</h2>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr class="bg-dark text-white">
                                <td>EnrollmentID</td>    
                                <td>UserID</td>    
                                <td>courseID</td>    
                                <td>EnrollmentDate</td>
                                <td>Accept enrollment</td>
                            </tr>
                            <tr>
                            <?php
                                
                                while($row = mysqli_fetch_assoc($result))
                                {
                                ?>
                            <td><?php echo $row['EnrollmentID']; ?></td>
                            <td><?php $userID = $row['UserID']; echo $userID?></td>
                            <td><?php $courseID = $row['CourseID']; echo $courseID?></td>
                            <td><?php echo $row['EnrollmentDate']; ?></td>
                            <td><a href="../Assets/PHP/acceptEnrollment.php?userid=<?php echo $userID; ?>&courseid=<?php echo $courseID ?>" class="btn btn-primary">Accept</td>    
                            </tr>    
                            <?php
                                    }
                                ?>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <button class="button button1" id="buttonGoBack" onclick="location.href = 'adminView.php';">Back</button>
    </div>

    
</body>
</html>