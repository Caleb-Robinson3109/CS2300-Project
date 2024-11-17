<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wage Wizards</title>
</head>
<body>
<?php
session_start();

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "hr_database"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$fname = $_SESSION['edit_fname'];
$lname = $_SESSION['edit_lname'];
$phone = $_SESSION['edit_phone'];
$email = $_SESSION['edit_email'];
$role = $_SESSION['edit_role'];
$curr_role = $_SESSION['curr_role'];
$ssn = $_SESSION['edit_ssn'];

$employee = "UPDATE employee SET role = '$role' WHERE E_Ssn = '$ssn'";
if ($conn->query($employee) === FALSE) {
    echo "Error: " . $employee . "<br>" . $conn->error;
}

/*$emp_role_q = "SELECT role AS role FROM employee WHERE E_Ssn = '$ssn'";
$emp_role_r = $conn->query($emp_role_q);
$emp_role = $emp_role_r->fetch_assoc();
$curr_role = $emp_role['role'];*/

//echo "Current role: " . $curr_role . "<br>";
//echo "New role: " . $role . "<br>";

//does the role
if($curr_role == "hr"){
    $hr = "UPDATE hr SET F_name = '$fname', L_name = '$lname', phone_no = '$phone', email = '$email' WHERE hr_Ssn = '$ssn'";
    if ($conn->query($hr) === FALSE) {
        echo "Error: " . $hr . "<br>" . $conn->error;
    }
}
elseif($curr_role == "accountant"){
    $accountant = "UPDATE accountant SET F_name = '$fname', L_name = '$lname', phone_no = '$phone', email = '$email' WHERE acc_Ssn = '$ssn'";
    if ($conn->query($accountant) === FALSE) {
        echo "Error: " . $accountant . "<br>" . $conn->error;
    }
}
elseif($curr_role == "associate"){
    $associate = "UPDATE associate SET F_name = '$fname', L_name = '$lname', phone_no = '$phone', email = '$email' WHERE acc_Ssn = '$ssn'";
    if ($conn->query($associate) === FALSE) {
        echo "Error: " . $associate . "<br>" . $conn->error;
    }
}

//changes the role if needed
if($curr_role != $role){
    //echo "hello there";
    $conn->query("SET foreign_key_checks = 0;");
    if($curr_role == "hr")
    {
        $remove = "DELETE FROM hr WHERE hr_Ssn = '$ssn'";
        if ($conn->query($remove) === FALSE) {
            echo "Error: " . $remove . "<br>" . $conn->error;
        }
        //gets rid of all the accuss table where this was an hr
        $conn->query("DELETE FROM access WHERE hr_Ssn = '$ssn'");
        //$remove_access_q = "SELECT hr_Ssn, username FROM access WHERE hr_ssn = '$ssn'";
        //$remove_access_result = $conn->query($remove_access_q);
        //while($remove_accues_row = $remove_access_result->fetch_assoc();){
        //    $remove_access_row_q = "DELETE FROM access WHERE hr"
        //}
    }
    elseif($curr_role == "accountant"){
        $remove = "DELETE FROM accountant WHERE acc_Ssn = '$ssn'";
        if ($conn->query($remove) === FALSE) {
            echo "Error: " . $remove . "<br>" . $conn->error;
        }
    }
    elseif($curr_role == "associate"){
        $remove = "DELETE FROM associate WHERE acc_Ssn = '$ssn'";
        if ($conn->query($remove) === FALSE) {
            echo "Error: " . $remove . "<br>" . $conn->error;
        }
    }
    if($role == "hr"){
        $insert = "INSERT INTO hr (hr_Ssn, email, F_name, L_name, phone_no) VALUES ('$ssn', '$email', '$fname', '$lname', '$phone')";
        if ($conn->query($insert) === FALSE) {
            echo "Error: " . $insert . "<br>" . $conn->error;
        }
        //add hr to accuss table
    }
    elseif($role == "accountant"){
        $insert = "INSERT INTO accountant (acc_Ssn, email, F_name, L_name, phone_no) VALUES ('$ssn', '$email', '$fname', '$lname', '$phone')";
        if ($conn->query($insert) === FALSE) {
            echo "Error: " . $insert . "<br>" . $conn->error;
        }
    }
    elseif($role == "associate"){
        $insert = "INSERT INTO associate (acc_Ssn, email, F_name, L_name, phone_no) VALUES ('$ssn', '$email', '$fname', '$lname', '$phone')";
        if ($conn->query($insert) === FALSE) {
            echo "Error: " . $insert . "<br>" . $conn->error;
        }
    }
    $conn->query("SET foreign_key_checks = 1;");
}

$conn->close();

header("Location: ../hr_home/hr_home.html");
exit();
?>
</body>
</html>

