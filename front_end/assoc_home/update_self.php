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
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    //$password = $_POST['password'];
    //$username = $_POST['username'];
    $acc = $_POST['acc'];
    $rout = $_POST['rout'];
    $role = $_POST['curr_role'];

    if($role == "hr"){
        $conn->query("UPDATE hr SET F_name = '$fname' WHERE hr_Ssn = '" . $_SESSION['ssn'] . "'");
        $conn->query("UPDATE hr SET L_name = '$lname' WHERE hr_Ssn = '" . $_SESSION['ssn'] . "'");
        $conn->query("UPDATE hr SET email = '$email' WHERE hr_Ssn = '" . $_SESSION['ssn'] . "'");
        $conn->query("UPDATE hr SET phone_no = '$phone' WHERE hr_Ssn = '" . $_SESSION['ssn'] . "'");
    }
    elseif($role == "accountant"){

    }
    elseif($role == "associate"){

    }
    //gets old username
    /*$username_q = "SELECT username AS username FROM has WHERE E_Ssn = '" . $_SESSION['ssn'] . "'";
    $username_r = $conn->query($username_q);
    $username_row = $username_r->fetch_assoc();
    $old_username = $username_row['username'];
    //sets new username in access, login, has
    $conn->query("UPDATE access SET username = '$username' WHERE username = '$old_username'");
    $conn->query("UPDATE login SET password = '$password' WHERE username = '$old_username'");
    $conn->query("UPDATE login SET username = '$username' WHERE username = '$old_username'");
    $conn->query("UPDATE has SET username = '$username' WHERE username = '$old_username'");*/

    //checks to see if a pay tables have been init for emps ssn
    $bank_q = "SELECT pay.acc_num AS acc, pay.rout_num AS rout, pay.id AS pid FROM gets
        JOIN pay ON gets.pay_id = pay.id
        WHERE gets.E_Ssn = '" . $_SESSION['ssn'] . "'";
    $bank_r = $conn->query($bank_q);
    if($bank_r->num_rows > 0){
        $bank_row = $bank_r->fetch_assoc();
        $pid = $bank_row['pid'];
        $conn->query("UPDATE pay SET acc_num = '$acc' WHERE id = '$pid'");
        $conn->query("UPDATE pay SET rout_num = '$rout' WHERE id = '$pid'");
    }
    else{
        //init pay and get
        $conn->query("INSERT INTO pay (id, acc_num, rout_num) VALUES ('" . $_SESSION['ssn'] . "', '$acc', '$rout')");
        $gets_r = $conn->query("INSERT INTO gets (E_Ssn, pay_id) VALUES ('" . $_SESSION['ssn'] . "', '" . $_SESSION['ssn'] . "')");
        if ($gets_r === FALSE) {
            // If there was an error, output the SQL query and the error message
            //echo "Error with query: " . $sql . "<br>";
            echo "MySQL error: " . $conn->error;
        }
    }
    
}

$conn->close();

header("Location: assoc_home.php");
exit();