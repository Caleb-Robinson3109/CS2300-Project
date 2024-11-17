<?php
session_start();

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "hr_database"; 

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

    $row = $result->fetch_assoc();

    $num_hrs = $row['hrs'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wage Wizards</title>
</head>
<body>
    <?php
        if($num_hrs > 1 || $role == "hr"){
            $_SESSION['edit_fname'] = $fname;
            $_SESSION['edit_lname'] = $lname;
            $_SESSION['edit_phone'] = $phone;
            $_SESSION['edit_email'] = $email;
            $_SESSION['edit_role'] = $role;
            $_SESSION['curr_role'] = $curr_role;
            $_SESSION['edit_ssn'] = $ssn;
            header("Location: update_employee.php");
            exit();
        }
        else{
            echo "<script type='text/javascript'>
                    alert('Must have at least one HR in the company.');
                    window.history.back();
                </script>";
                exit();
        }
        $conn->close();
    ?>
</body>
</html>
