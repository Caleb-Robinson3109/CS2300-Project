<?php
session_start();

$db_servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "hr_database";

//connect to db
$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

//check connection
if($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

//checks to make sure the user is an hr
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $sql = "SELECT * FROM hr WHERE hr_Ssn = ''";
    $result = $conn->query($sql);

    if($result->num_row > 0)
    {

    }
    else{
        echo "<script type='text/javascript'>
                alert('YO');
                window.history.back();
              </script>";
              exit();
    }
}