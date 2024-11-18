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

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $ssn = $_POST['e_ssn'];

    $role_q = "SELECT role AS role FROM employee WHERE E_Ssn = '$ssn'";
    $role_r = $conn->query($role_q);
    $role_row = $role_r->fetch_assoc();
    $role = $role_row['role'];

    $sql = "SELECT COUNT(DISTINCT hr.hr_Ssn) AS hrs 
        FROM employs 
        JOIN hr ON employs.E_Ssn = hr.hr_Ssn 
        WHERE employs.C_name = '".$_SESSION['company_name']."'";

    $result = $conn->query($sql);

    if ($result === FALSE) {
        // If there was an error, output the SQL query and the error message
        echo "Error with query: " . $sql . "<br>";
        echo "MySQL error: " . $conn->error;
    }

    $row_hrs = $result->fetch_assoc();

    $num_hrs = $row_hrs['hrs'];
    //makes sure there is at least one hr in the company
    if($num_hrs > 1 || $role != "hr"){
        $get_emp_q = "SELECT hr.hr_Ssn AS ssn,
            'hr' AS role,
            has.username AS username
            FROM hr JOIN has ON hr.hr_Ssn = has.E_Ssn
            WHERE hr.hr_Ssn = '$ssn'
            UNION SELECT accountant.acc_Ssn AS ssn,
            'accountant' AS role,
            has.username AS username
            FROM accountant JOIN has ON accountant.acc_Ssn = has.E_Ssn
            WHERE accountant.acc_Ssn = '$ssn'
            UNION SELECT associate.acc_Ssn AS ssn,
            'associate' AS role,
            has.username AS username
            FROM associate JOIN has ON associate.acc_Ssn = has.E_Ssn
            WHERE associate.acc_Ssn = '$ssn'";

        $get_emp_r = $conn->query($get_emp_q);

        $gets = $conn->query("SELECT pay_id AS pid FROM gets WHERE E_Ssn = '$ssn'");
        $pay_row = $gets->fetch_assoc();

        $get_emp_row = $get_emp_r->fetch_assoc();
        $emp_ssn = $get_emp_row['ssn'];
        $emp_role = $get_emp_row['role'];
        $emp_username = $get_emp_row['username'];
        $emp_pid = $pay_row['pid'];

        //goes through and deletes the employee from all the tables
        if($role == "hr"){
            $conn->query("DELETE FROM access WHERE hr_Ssn = '$emp_ssn'");
        }
        if($role == "accountant"){
            $conn->query("DELETE FROM manages WHERE E_Ssn = '$emp_ssn'");
        }
        $conn->query("DELETE FROM access WHERE username = '$emp_username'");
        $conn->query("DELETE FROM has WHERE E_Ssn = '$emp_ssn'");
        $conn->query("DELETE FROM login WHERE username = '$emp_username'");
        $conn->query("DELETE FROM manages WHERE pay_id = '$emp_pid'");
        $conn->query("DELETE FROM pay_details WHERE pay_id = '$emp_pid'");
        $conn->query("DELETE FROM gets WHERE E_Ssn = '$emp_ssn'");
        $conn->query("DELETE FROM pay WHERE id = '$emp_pid'");
        $conn->query("DELETE FROM employs WHERE E_Ssn = '$emp_ssn'");
        if($emp_role == "hr"){
            $conn->query("DELETE FROM hr WHERE hr_Ssn = '$emp_ssn'");
        }
        elseif($emp_role == "accountant"){
            $conn->query("DELETE FROM accountant WHERE acc_Ssn = '$emp_ssn'");
        }
        elseif($emp_role == "associate"){
            $conn->query("DELETE FROM associate WHERE acc_Ssn = '$emp_ssn'");
        }
        $conn->query("DELETE FROM employee WHERE E_Ssn = '$emp_ssn'");
        header("Location: hr_home.html");
        exit();
    }
    else{
        echo "<script type='text/javascript'>
                alert('Must have at least one HR in the company.');
                window.history.back();
            </script>";
    }
}
$conn->close();
//header("Location: hr_home.html");
//exit();
?>
</body>
</html>