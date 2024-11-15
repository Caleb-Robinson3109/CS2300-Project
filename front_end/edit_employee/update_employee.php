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

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $ssn = $_POST['ssn'];

    $employee = "UPDATE employee SET role = '$role' WHERE E_Ssn = '$ssn'";
    if ($conn->query($employee) === FALSE) {
        echo "Error: " . $employee . "<br>" . $conn->error;
    }

    $emp_role_q = "SELECT role AS role FROM employee WHERE E_Ssn = '$ssn'";
    $emp_role_r = $conn->query($emp_role_q);
    $emp_role = $emp_role_r->fetch_assoc();

    //does the role
    $change_role = false;
    if($role == "hr"){
        $hr = "UPDATE hr SET F_name = '$fname', L_name = '$lname', phone_no = '$phone', email = '$email' WHERE hr_Ssn = '$ssn'";
        if ($conn->query($hr) === FALSE) {
            echo "Error: " . $hr . "<br>" . $conn->error;
        }
        if("hr" != $emp_role['role']){
            $change_role = true;
        }
    }
    elseif($role == "accountant"){
        $accountant = "UPDATE accountant SET F_name = '$fname', L_name = '$lname', phone_no = '$phone', email = '$email' WHERE acc_Ssn = '$ssn'";
        if ($conn->query($accountant) === FALSE) {
            echo "Error: " . $accountant . "<br>" . $conn->error;
        }
        if("accountant" != $emp_role['role']){
            $change_role = true;
        }
    }
    elseif($role == "associate"){
        $associate = "UPDATE aassociate SET F_name = '$fname', L_name = '$lname', phone_no = '$phone', email = '$email' WHERE acc_Ssn = '$ssn'";
        if ($conn->query($associate) === FALSE) {
            echo "Error: " . $associate . "<br>" . $conn->error;
        }
        if("associate" != $emp_role['role']){
            $change_role = true;
        }
    }

    //changes the role if needed
    if($change_role){
        $stop = false;
        if($emp_role['role'] == "hr")
        {
            //makes sure that there is at least one hr in the company
            $num_hr = "SELECT hr.hr_Ssn FROM hr JOIN employs ON hr.hr_Ssn = employs.E_Ssn WHERE employs.C_name = '".$_SESSION['company_name']."' AND  hr.hr"
            $remove = "DELETE FROM hr WHERE hr_Ssn = '$ssn'";
            if ($conn->query($remove) === FALSE) {
                echo "Error: " . $remove . "<br>" . $conn->error;
            }
        }
        elseif($emp_role['role'] == "accountant"){
            $remove = "DELETE FROM accountant WHERE acc_Ssn = '$ssn'";
            if ($conn->query($remove) === FALSE) {
                echo "Error: " . $remove . "<br>" . $conn->error;
            }
        }
        elseif($emp_role['role'] == "associate"){
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
        elseif($role == "associatet"){
            $insert = "INSERT INTO associate (acc_Ssn, email, F_name, L_name, phone_no) VALUES ('$ssn', '$email', '$fname', '$lname', '$phone')";
            if ($conn->query($insert) === FALSE) {
                echo "Error: " . $insert . "<br>" . $conn->error;
            }
        }
    }
}

header("Location: ../hr_home/hr_home.html");
exit();

$conn->close();
