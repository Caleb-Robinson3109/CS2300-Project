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

$lookup_ssn = $_POST['ssn'];
$is_hr = $conn->query("SELECT * FROM hr WHERE hr_Ssn = '$lookup_ssn'");
$is_acc = $conn->query("SELECT * FROM accountant WHERE acc_Ssn = '$lookup_ssn'");
$is_ass = $conn->query("SELECT * FROM associate WHERE acc_Ssn = '$lookup_ssn'");

// Get the employees' information
if($is_hr->num_rows > 0)
{
    $emp = "hr";
    $emp_ssn = "hr_Ssn";
}
elseif($is_acc->num_rows > 0){
    $emp = "accountant";
    $emp_ssn = "acc_Ssn";
}
elseif($is_ass->num_rows > 0){
    $emp = "associate";
    $emp_ssn = "acc_Ssn";
}
else{
    $emp = "none";
}

if($emp != "none"){
    $sql = "SELECT has.E_Ssn AS ssn, `$emp`.email AS email, `$emp`.F_name AS fname, `$emp`.L_name AS lname, `$emp`.phone_no AS phone, login.username AS username, login.password AS password
        FROM `$emp` JOIN has ON `$emp`.`$emp_ssn` = has.E_Ssn 
        JOIN login ON has.username = login.username 
        WHERE `$emp`.`$emp_ssn` = '$lookup_ssn'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<form action="update_employee.php" method="POST">';
            echo '<label for="employee_info" class="item">Update Employee Information</label>';
            echo '<div>';
            echo '<label for="fname">First Name: </label>';
            echo '<input type="text" id="fname" name="fname" value="' . $row['fname'] . '" required>';
            echo '</div>';
            echo '<div>';
            echo '<label for="lname">Last Name: </label>';
            echo '<input type="text" id="lname" name="lname" value="' . $row['lname'] . '" required>';
            echo '</div>';
            echo '<div>';
            echo '<p>SSN: ' . $row['ssn'] . '</p>';
            echo '</div>';
            echo '<div>';
            echo '<label for="email">Email: </label>';
            echo '<input type="text" id="email" name="email" value="' . $row['email'] . '" required>';
            echo '</div>';
            echo '<div>';
            echo '<label for="phone">Phone Number: </label>';
            echo '<input type="text" id="phone" name="phone" value="' . $row['phone'] . '" required>';
            echo '<div>';
            echo '<p>Password: ' . $row['password'] . '</p>';
            echo '</div>';
            echo '<div>';
            echo '<p>Username: ' . $row['username'] . '</p>';
            echo '</div>';
            echo '<label>Privlage: </label><br>';
            echo '<input type="radio" name="privlage" value="associate" id="associate" <?php if ($emp == 'associate') echo 'checked'; ?>> Associate';
            echo '<input type="radio" name="privlage" value="accountant" id="accountant" <?php if ($emp == 'accountant') echo 'checked'; ?>> Accountant';
            echo '<input type="radio" name="privlage" value="hr" id="hr" <?php if ($emp == 'hr') echo 'checked'; ?>> Human Reasources';
            echo '<br><br>';
            echo '</div>';
            echo '<button type="submit" class="item">Enter</button>';
            echo '</form>';
            echo '';
        }
    }
}
else{
    echo "Employee with SSN '$lookup_ssn' not found.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wage Wizards</title>
</head>
<body>
</body>
</html>