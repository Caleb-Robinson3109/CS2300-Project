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
    $privlage = $_POST['role'];

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

    //goes through and adds the data to the db and drops things if fails
    if (mysqli_query($conn, $employee_q) === false) {
        echo "Error: " . $employee_q . "<br>" . mysqli_error($conn);
    }
    elseif (mysqli_query($conn, $role_q) === false) {
        echo "Error: " . $role_q . "<br>" . mysqli_error($conn);
        mysqli_query($conn, "DELETE FROM employee WHERE E_Ssn = '$ssn'");
    }
    else if (mysqli_query($conn, $employs_q) === false) {
        echo "Error: " . $employs_q . "<br>" . mysqli_error($conn);
        if($privlage == "hr"){
            mysqli_query($conn, "DELETE FROM hr WHERE hr_Ssn = '$ssn'");
        }
        elseif($privlage == "accountant"){
            mysqli_query($conn, "DELETE FROM accountant WHERE acc_Ssn = '$ssn'");
        }
        else{
            mysqli_query($conn, "DELETE FROM associate WHERE acc_Ssn = '$ssn'");
        }
        mysqli_query($conn, "DELETE FROM employee WHERE E_Ssn = '$ssn'");
    }   
    elseif (mysqli_query($conn, $login_q) === false) {
        echo "Error: " . $login_q . "<br>" . mysqli_error($conn);
        mysqli_query($conn, "DELETE FROM employs WHERE E_Ssn = '$ssn'");
        if($privlage == "hr"){
            mysqli_query($conn, "DELETE FROM hr WHERE hr_Ssn = '$ssn'");
        }
        elseif($privlage == "accountant"){
            mysqli_query($conn, "DELETE FROM accountant WHERE acc_Ssn = '$ssn'");
        }
        else{
            mysqli_query($conn, "DELETE FROM associate WHERE acc_Ssn = '$ssn'");
        }
        mysqli_query($conn, "DELETE FROM employee WHERE E_Ssn = '$ssn'");
    }
    elseif (mysqli_query($conn, $has_q) === false) {
        echo "Error: " . $has_q . "<br>" . mysqli_error($conn);
        mysqli_query($conn, "DELETE FROM login WHERE username = '$username'");
        mysqli_query($conn, "DELETE FROM employs WHERE E_Ssn = '$ssn'");
        if($privlage == "hr"){
            mysqli_query($conn, "DELETE FROM hr WHERE hr_Ssn = '$ssn'");
        }
        elseif($privlage == "accountant"){
            mysqli_query($conn, "DELETE FROM accountant WHERE acc_Ssn = '$ssn'");
        }
        else{
            mysqli_query($conn, "DELETE FROM associate WHERE acc_Ssn = '$ssn'");
        }
        mysqli_query($conn, "DELETE FROM employee WHERE E_Ssn = '$ssn'");
    }
    else{
        //adds ech of the hr to the new employess accuss table
        $hr_ssn_q = "SELECT hr.hr_Ssn AS ssn FROM employs JOIN hr ON employs.E_Ssn = hr.hr_Ssn WHERE employs.C_name =  '".$_SESSION['company_name']."'";
        $hr_ssn_result = mysqli_query($conn, $hr_ssn_q);
        if ($hr_ssn_result === false) {
            echo "Error: " . $hr_ssn_q . "<br>" . mysqli_error($conn);
        }
        if ($hr_ssn_result->num_rows > 0) {
            while ($row = $hr_ssn_result->fetch_assoc()) {
                $qu = "INSERT INTO access (hr_Ssn, username, password) VALUES ('" . $row['ssn'] . "', '$username', '$password')";
                if (mysqli_query($conn, $qu) === false) {
                    echo "Error: " . $qu . "<br>" . mysqli_error($conn);
                }
            }
        }
        //goes back to hr home page
        header("Location: ../hr_home/hr_home.html");
        exit();
    }  
}

$conn->close();