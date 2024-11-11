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
    $company_name = $_POST['c_name'];
    $company_address = $_POST['c_address'];
    $admin_fname = $_POST['hr_fname'];
    $admin_lname = $_POST['hr_lname'];
    $admin_ssn = $_POST['hr_ssn'];
    $admin_username = $_POST['hr_username'];
    $admin_password = $_POST['hr_password'];

    //add the company and the hr to the database
    $conn->query("INSERT INTO company VALUES '$company_name', '$company_address'");
    $conn->query("INSERT INTO employee VALUES '$admin_ssn', 'hr'");
    $conn->query("INSERT INTO employs VALUES '$company_name', '$admin_ssn'");
    $conn->query("INSERT INTO hr VALUES '$admin_ssn', NULL '$admin_fname', '$admin_lname', NULL");
    $conn->query("INSERT INTO login VALUES '$admin_username', '$admin_password'");
    $conn->query("INSERT INTO has VALUES '$admin_ssn', '$admin_username'");

    //goes back to the enter company page (index.html)
    header("Location: ../index.html");
    exit();
}

$conn->close();
