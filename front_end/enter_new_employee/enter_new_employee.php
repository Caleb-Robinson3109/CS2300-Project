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

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $ssn = $_POST['ssn'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $privlage = $_POST['privlage'];

    //insert info into tables 
    $employee_q = "INSERT INTO employee (E_Ssn, role) VALUES ('$ssn', '$privlage')";
    if($privlage == "hr"){
        $role_q = "INSERT INTO hr (hr_Ssn, email, F_name, L_name, phone_no) VALUES ('$ssn', '$email', '$fname', '$lname', '$phone')";
    }
    elseif($privlage == "accountant"){
        $role_q = "INSERT INTO accountant (acc_Ssn, email, F_name, L_name, phone_no) VALUES ('$ssn', '$email', '$fname', '$lname', '$phone')";
    }
    else{
        $role_q = "INSERT INTO associate (acc_Ssn, email, F_name, L_name, phone_no) VALUES ('$ssn', '$email', '$fname', '$lname', '$phone')";
    }
    $employs_q = "INSERT INTO employs (C_name, E_Ssn) VALUES ('".$_SESSION['company_name']."', '$ssn')";
    $login_q = "INSERT INTO login (username, password) VALUES ('$username', '$password')";
    $has_q = "INSERT INTO has (E_Ssn, username) VALUES ('$ssn', '$username')";
    //$access_q = "INSERT INTO access (hr_Ssn, username, password) VALUES ('$ssn', '$username', ;$password')";
}

$conn->close();