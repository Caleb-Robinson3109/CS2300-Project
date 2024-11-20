<?php
session_start();
$db_servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "hr_database";
//creates the connection
$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $company_name = $_POST['company_name'];
    
    $sql = "SELECT Name FROM company WHERE Name = '$company_name'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        //redirct to login.html
        $_SESSION['company_name'] = $company_name;
        header("Location: /login/login.html");
        exit();
    }
    else{
        echo "<script type='text/javascript'>
                alert('Company not reconized.');
                window.history.back();
              </script>";
        exit();
    }
}

// Close the database connection
$conn->close();
