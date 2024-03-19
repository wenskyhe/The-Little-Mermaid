<?php
    // login.php
    session_start(); // Start the session.
    $servername = "localhost";
    $username = "root"; // default XAMPP MySQL username
    $password = ""; // default XAMPP MySQL password is empty
    $dbname = "aquademia";

    try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $uname = $_POST['uname'];
    $psw = $_POST['psw'];

    $stmt = $conn->prepare("SELECT UserID, PasswordHash, UserType FROM Users WHERE Username = :uname");
    $stmt->bindParam(':uname', $uname);
    $stmt->execute(s);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && ($psw == $user['PasswordHash'])) {
        // Password is correct
        $_SESSION["loggedin"] = true;
        $_SESSION["UserID"] = $user['UserID'];
        $_SESSION["Username"] = $uname;
        $_SESSION["UserType"] = $user['UserType'];

        if($user['UserType'] == "Admin"){
            header("Location: ../../Pages/adminView.php");
        }
        elseif($user['UserType'] == "Student"){
            header("Location: ../../Pages/studentView.php");
        }
        elseif($user['UserType'] == "Teacher"){
            header("Location: ../../Pages/teacherView.php");
        }
    } else {
        // Display an error message
        $alertMessage = "The password you entered was incorrect!";
        echo $alertMessage;
        header("Location: ../../Pages/login.html");
    }

    } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    }
    ?>