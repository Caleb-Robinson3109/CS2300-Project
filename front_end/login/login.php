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

//gets the password and username from login.html
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //gets data
    $username = $_POST['username'];
    $password = $_POST['password'];

    //do sql quary to see if the username and password are in the db
    $sql = "SELECT login.username, login.password FROM login JOIN has ON login.username = has.username JOIN employes ON has.E_Ssn = employs.E_Ssn WHERE login.username = '$username' AND employs.C_name = '$_SESSION['company_name']'";
    $result = $conn->query($sql);

    //there is username and passwrod combo in the db
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if($row['password'] == $password)
        {
            $_SESSION['username'] = $username;
            header("Location: ../select_role/select_role.html");
            exit();
        }
        else{
            echo "<script type='text/javascript'>
                alert('Username and password not found.');
                window.history.back();
              </script>";
              exit();
        }
    }
    else{
        echo "<script type='text/javascript'>
                alert('Username and password not found.');
                window.history.back();
              </script>";
              exit();
    }
}

// Close the database connection
$conn->close();