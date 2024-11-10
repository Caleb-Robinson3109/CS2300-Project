<?php
session_start();

$db_servername = "localhost";
$db_username = "root";
$db_password = "password";
$db_name = "hr_database";
//creates the connection
$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//gets the password and username from login.html
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //gets data
    $username = $_POST['username'];
    $password = $_POST['password'];

    //do sql quary to see if the username and password are in the db
    $sql_username = "SELECT username FROM LOGIN WHERE username = '$username'";
    $sql_password = "SELECT password FROM LOGIN WHERE username = '$username'";
    $result_username = $conn->query($sql_username);
    $result_password = $conn->query($sql_password);

    //there is username and passwrod combo in the db
    if($result_password->num_rows > 0 && $result_username->num_rows > 0){
        //do stuff
    }
}




