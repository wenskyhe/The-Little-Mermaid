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

    $stmt = $conn->prepare("SELECT UserID, PasswordHash FROM Users WHERE Username = :uname");
    $stmt->bindParam(':uname', $uname);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($psw, $user['PasswordHash'])) {
        // Password is correct
        $_SESSION["loggedin"] = true;
        $_SESSION["UserID"] = $user['UserID'];
        $_SESSION["Username"] = $uname;

        // Redirect to welcome page
        echo "Welcome back " . $uname . " we are Happy to see you Again!";
    } else {
        // Display an error message
        echo "The password you entered was not valid.";
    }
    } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    }
    ?>