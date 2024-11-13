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
    $admin_email = $_POST['hr_email'];
    $admin_phone = $_POST['hr_phone'];
    $admin_username = $_POST['hr_username'];
    $admin_password = $_POST['hr_password'];

    //add the company and the hr to the database
    $company_q = "INSERT INTO company (Name, Address) VALUES ('$company_name', '$company_address')";
    $employee_q = "INSERT INTO employee (E_Ssn, role) VALUES ('$admin_ssn', 'hr')";
    $employs_q = "INSERT INTO employs (c_name, E_Ssn) VALUES ('$company_name', '$admin_ssn')";
    $hr_q = "INSERT INTO hr (hr_Ssn, email, F_name, L_name, phone_no) VALUES ('$admin_ssn', '$admin_email', '$admin_fname', '$admin_lname', '$admin_phone')";
    $login_q = "INSERT INTO login (username, password) VALUES ('$admin_username', '$admin_password')";
    $has_q = "INSERT INTO has (E_Ssn, username) VALUES ('$admin_ssn', '$admin_username')";
    $access_q = "INSERT INTO access (hr_Ssn, username, password) VALUES ('$admin_ssn', '$admin_username', '$admin_password')";

    if (mysqli_query($conn, $company_q) === false) {
        echo "Error: " . $company_q . "<br>" . mysqli_error($conn);
    } 
    elseif (mysqli_query($conn, $employee_q) === false) {
        echo "Error: " . $employee_q . "<br>" . mysqli_error($conn);
        //deletes previous entries
        mysqli_query($conn, "DELETE FROM company WHERE Name = '$company_name'");
    } 
    elseif (mysqli_query($conn, $employs_q) === false) {
        echo "Error: " . $employs_q . "<br>" . mysqli_error($conn);
        //deletes previous entries
        mysqli_query($conn, "DELETE FROM employee WHERE E_Ssn = '$admin_ssn'");
        mysqli_query($conn, "DELETE FROM company WHERE Name = '$company_name'");
    } 
    elseif (mysqli_query($conn, $hr_q) === false) {
        echo "Error: " . $hr_q . "<br>" . mysqli_error($conn);
        //deletes previous entries
        mysqli_query($conn, "DELETE FROM employs WHERE C_name = '$company_name' AND E_Ssn = '$admin_ssn'");
        mysqli_query($conn, "DELETE FROM employee WHERE E_Ssn = '$admin_ssn'");
        mysqli_query($conn, "DELETE FROM company WHERE Name = '$company_name'");
    } 
    elseif (mysqli_query($conn, $login_q) === false) {
        echo "Error: " . $login_q . "<br>" . mysqli_error($conn);
        //deletes previous entries
        mysqli_query($conn, "DELETE FROM hr WHERE hr_Ssn = '$admin_ssn'");
        mysqli_query($conn, "DELETE FROM employs WHERE C_name = '$company_name' AND E_Ssn = '$admin_ssn'");
        mysqli_query($conn, "DELETE FROM employee WHERE E_Ssn = '$admin_ssn'");
        mysqli_query($conn, "DELETE FROM company WHERE Name = '$company_name'");
    }
    elseif (mysqli_query($conn, $has_q) === false) {
        echo "Error: " . $has_q . "<br>" . mysqli_error($conn);
        //deletes previous entries
        mysqli_query($conn, "DELETE FROM login WHERE username = '$admin_username'");
        mysqli_query($conn, "DELETE FROM hr WHERE hr_Ssn = '$admin_ssn'");
        mysqli_query($conn, "DELETE FROM employs WHERE C_name = '$company_name' AND E_Ssn = '$admin_ssn'");
        mysqli_query($conn, "DELETE FROM employee WHERE E_Ssn = '$admin_ssn'");
        mysqli_query($conn, "DELETE FROM company WHERE Name = '$company_name'");
    }
    elseif(mysqli_query($conn, $access_q) === false){
        echo "Error: " . $access_q . "<br>" . mysqli_error($conn);
        //deletes previous entries
        mysqli_query($conn, "DELETE FROM has WHERE E_Ssn = '$admin_ssn'");
        mysqli_query($conn, "DELETE FROM login WHERE username = '$admin_username'");
        mysqli_query($conn, "DELETE FROM hr WHERE hr_Ssn = '$admin_ssn'");
        mysqli_query($conn, "DELETE FROM employs WHERE C_name = '$company_name' AND E_Ssn = '$admin_ssn'");
        mysqli_query($conn, "DELETE FROM employee WHERE E_Ssn = '$admin_ssn'");
        mysqli_query($conn, "DELETE FROM company WHERE Name = '$company_name'");
    } 
    else{
        //goes back to the enter company page (index.html)
        header("Location: ../index.html");
        exit();
    }
}

$conn->close();
