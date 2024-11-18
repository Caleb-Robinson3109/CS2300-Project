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

    $sql = "SELECT pay.acc_num AS acc, pay.rout_num AS rout, hr.hr_Ssn AS ssn, 'hr' AS role, hr.F_name AS fname, hr.L_name AS lname, hr.email AS email, hr.phone_no AS phone, login.username AS username, login.password AS password
    FROM hr
    JOIN has ON hr.hr_Ssn = has.E_Ssn
    JOIN login ON has.username = login.username
    JOIN gets ON hr.hr_Ssn = gets.E_Ssn
    JOIN pay ON gets.pay_id = pay.id
    WHERE hr.hr_Ssn = '" . $_SESSION['ssn'] . "'
    UNION
    SELECT pay.acc_num AS acc, pay.rout_num AS rout, accountant.acc_Ssn AS ssn, 'accountant' AS role, accountant.F_name AS fname, accountant.L_name AS lname, accountant.email AS email, accountant.phone_no AS phone, login.username AS username, login.password AS password
    FROM accountant
    JOIN has ON accountant.acc_Ssn = has.E_Ssn
    JOIN login ON has.username = login.username
    JOIN gets ON accountant.acc_Ssn = gets.E_Ssn
    JOIN pay ON gets.pay_id = pay.id
    WHERE accountant.acc_Ssn = '" . $_SESSION['ssn'] . "'
    UNION
    SELECT pay.acc_num AS acc, pay.rout_num AS rout, associate.acc_Ssn AS ssn, 'associate' AS role, associate.F_name AS fname, associate.L_name AS lname, associate.email AS email, associate.phone_no AS phone, login.username AS username, login.password AS password
    FROM associate
    JOIN has ON associate.acc_Ssn = has.E_Ssn
    JOIN login ON has.username = login.username
    JOIN gets ON associate.acc_Ssn = gets.E_Ssn
    JOIN pay ON gets.pay_id = pay.id
    WHERE associate.acc_Ssn = '" . $_SESSION['ssn'] . "'";

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
                echo '<form action="update_self.php" method="POST">';
                echo '<label for="self_info" class="item">Update Your Information</label>';
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
                echo '<label for="password">Password: </label>';
                echo '<input type="text" id="password" name="password" value="' . $row['password'] . '" required>';
                echo '</div>';
                echo '<div>';
                echo '<label for="username">Username: </label>';
                echo '<input type="text" id="username" name="username" value="' . $row['username'] . '" required>';
                echo '</div>';
                echo '<div>';
                echo '<label for="acc">Banking Account Number: </label>';
                echo '<input type="text" id="acc" name="acc" value="' . $row['acc'] . '" required>';
                echo '</div>';
                echo '<div>';
                echo '<label for="rout">Banking Routing Number: </label>';
                echo '<input type="text" id="rout" name="rout" value="' . $row['rout'] . '" required>';
                echo '</div>';
                echo '<button type="submit" class="item">Update</button>';
                echo '</form>';
            }
        }
    ?>
</body>
</html>