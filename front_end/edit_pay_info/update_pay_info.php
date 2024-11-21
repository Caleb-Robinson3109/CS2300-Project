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
    $salary = $_POST['salary'];
    $bonus = $_POST['bonus'];
    $benefits = $_POST['benefits'];
    $tax = $_POST['tax'];
    $ssn = $_POST['ssn'];

    //pay
    $pay_r = $conn->query("SELECT * FROM pay WHERE id = '$ssn'");
    if($pay_r->num_rows <=0){
        $conn->query("INSERT INTO pay (id) VALUES ('$ssn')");
    }

    //gets
    $gets_q = "SELECT E_Ssn AS ssn, pay_id AS pid FROM gets WHERE E_Ssn = '$ssn'";
    $gets_r = $conn->query($gets_q);
    if($gets_r->num_rows <= 0){
        $conn->query("INSERT INTO gets (E_Ssn, pay_id) VALUES ('$ssn', '$ssn')");
    }

    //pay_details
    $pd_q = "SELECT 
        pay_details.pay_id as pid
        FROM pay_details
        WHERE pay_id = '$ssn'";

    $pd_r = $conn->query($pd_q);
    if($pd_r->num_rows > 0){
        $pid_row = $pd_r->fetch_assoc();
        $pid = $e_ssn_row['pid'];
        $conn->query("UPDATE pay_details SET salary = '$salary' WHERE pay_id = '$ssn' AND DET_id = '$ssn'");
        $conn->query("UPDATE pay_details SET bonus = '$bonus' WHERE pay_id = '$ssn' AND DET_id = '$ssn'");
        $conn->query("UPDATE pay_details SET benefits = '$benefits' WHERE pay_id = '$ssn' AND DET_id = '$ssn'");
        $conn->query("UPDATE pay_details SET tax_rate = '$tax' WHERE pay_id = '$ssn' AND DET_id = '$ssn'");
    }
    else{
        $R = $conn->query("INSERT INTO pay_details (det_id, pay_id, salary, bonus, benefits, tax_rate) VALUES ('$ssn', '$ssn', '$salary', '$bonus', '$benefits', '$tax')");
        if ($R === FALSE) {
            // If there was an error, output the SQL query and the error message
            //echo "Error with query: " . $sql . "<br>";
            echo "MySQL error: " . $conn->error;
        }
    }
        
    header("Location: ../account_home/account_home.html");
    exit();

}
$conn->close();
?>
</body>
</html>

