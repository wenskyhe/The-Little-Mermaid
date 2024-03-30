<?php

    $servername = "localhost";
    $username = "root"; // default XAMPP MySQL username
    $password = ""; // default XAMPP MySQL password is empty
    $dbname = "aquademia";


    // Just the connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection Failed". $conn->connect_error);
    }

?>