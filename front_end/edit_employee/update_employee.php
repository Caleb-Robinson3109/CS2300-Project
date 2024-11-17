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
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $curr_role = $_POST['curr_role'];
    $ssn = $_POST['ssn'];

    echo "curr_role: '$curr_role'";
    echo "new_role: '$role'";


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

    if($num_hrs > 1 || $role == "hr" || $curr_role != "hr"){
        //updates employee table
        $employee = "UPDATE employee SET role = '$role' WHERE E_Ssn = '$ssn'";
        if ($conn->query($employee) === FALSE) {
            echo "Error: " . $employee . "<br>" . $conn->error;
        }

        //updates each role table for that ssn
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
            //removes from the current role
            if($curr_role == "hr")
            {
                //gets rid of all the accuss table where this was an hr
                $conn->query("DELETE FROM access WHERE hr_Ssn = '$ssn'");
                //removes the ssn from hr table
                $remove = "DELETE FROM hr WHERE hr_Ssn = '$ssn'";
                if ($conn->query($remove) === FALSE) {
                    echo "Error: " . $remove . "<br>" . $conn->error;
                }
            }
            elseif($curr_role == "accountant"){
                //removes that ssn from account table
                $remove = "DELETE FROM accountant WHERE acc_Ssn = '$ssn'";
                if ($conn->query($remove) === FALSE) {
                    echo "Error: " . $remove . "<br>" . $conn->error;
                }
            }
            elseif($curr_role == "associate"){
                //removes that ssn from assoc table
                $remove = "DELETE FROM associate WHERE acc_Ssn = '$ssn'";
                if ($conn->query($remove) === FALSE) {
                    echo "Error: " . $remove . "<br>" . $conn->error;
                }
            }
            //inserts into the new role table
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
        }
        header("Location: ../hr_home/hr_home.html");
        exit();
    }
    else{
        echo "<script type='text/javascript'>
                alert('Must have at least one HR in the company.');
                window.history.back();
            </script>";
            exit();
    }
}
$conn->close();
?>
</body>
</html>

