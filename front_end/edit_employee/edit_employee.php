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
    $lookup_ssn = $_POST['e_ssn'];

    $sql = "SELECT hr.hr_Ssn AS ssn, 'hr' AS role, hr.F_name AS fname, hr.L_name AS lname, hr.email AS email, hr.phone_no AS phone, login.username AS username, login.password AS password
    FROM hr
    JOIN has ON hr.hr_Ssn = has.E_Ssn
    JOIN login ON has.username = login.username
    WHERE hr.hr_Ssn = '$lookup_ssn'
    UNION
    SELECT accountant.acc_Ssn AS ssn, 'hr' AS role, accountant.F_name AS fname, accountant.L_name AS lname, accountant.email AS email, accountant.phone_no AS phone, login.username AS username, login.password AS password
    FROM accountant
    JOIN has ON accountant.acc_Ssn = has.E_Ssn
    JOIN login ON has.username = login.username
    WHERE accountant.acc_Ssn = '$lookup_ssn'
    UNION
    SELECT associate.acc_Ssn AS ssn, 'associate' AS role, associate.F_name AS fname, associate.L_name AS lname, associate.email AS email, associate.phone_no AS phone, login.username AS username, login.password AS password
    FROM associate
    JOIN has ON associate.acc_Ssn = has.E_Ssn
    JOIN login ON has.username = login.username
    WHERE associate.acc_Ssn = '$lookup_ssn'";

    $result = $conn->query($sql);
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
<button onclick="window.history.back()">Back</button>
    <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<form action="check_hr.php" method="POST">';
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
                echo '</div>';
                echo '<div>';
                echo '<p>Password: ' . $row['password'] . '</p>';
                echo '</div>';
                echo '<div>';
                echo '<p>Username: ' . $row['username'] . '</p>';
                echo '</div>';
    
                
                echo '<label>Privilege: </label><br>';
                echo '<input type="radio" name="role" value="associate" id="associate" ' . ($row['role'] == 'associate' ? 'checked' : '') . '> Associate';
                echo '<input type="radio" name="role" value="accountant" id="accountant" ' . ($row['role'] == 'accountant' ? 'checked' : '') . '> Accountant';
                echo '<input type="radio" name="role" value="hr" id="hr" ' . ($row['role'] == 'hr' ? 'checked' : '') . '> Human Resources';
                echo '<br><br>';
                echo '<input type="hidden" name="ssn" value="' . $row['ssn'] . '">';
                echo '<input type="hidden" name="curr_role" value="' . $row['role'] . '">';
                echo '<button type="submit" class="item">Update</button>';
                echo '</form>';
            }
        }
        else{
            echo 'Employee with the SSN not found.';
        }
    ?>
</body>
</html>