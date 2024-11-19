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
    $lookup_ssn = $_POST['ssn'];

    $sql = "SELECT gets.E_Ssn AS ssn,
        pay_deltails.salary AS salary,
        pay_details.bonus AS bonus
        pay_details.benefits AS benefits,
        pay_details.tax_rate AS tax
        FROM gets JOIN pay_details ON gets.pay_id = pay_details.pay_id
        WHERE gets.E_Ssn = '$lookup_ssn'";

    $ssn_q = "SELECT E_Ssn as ssn FROM employee WHERE E_Ssn = '$lookup_ssn'";
    $ssn_r = $conn->query($ssn_q);
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
                echo '<form action="update_pay_info.php" method="POST">';
                echo '<label for="employee_info" class="item">Update Employee Pay</label>';
                echo '<div>';
                echo '<label for="salary">Salary: </label>';
                echo '<input type="text" id="salary" name="salary" value="' . $row['salary'] . '" required>';
                echo '</div>';
                echo '<div>';
                echo '<label for="bonus">Bonus: </label>';
                echo '<input type="text" id="bonus" name="bonus" value="' . $row['bonus'] . '" required>';
                echo '</div>';
                echo '<div>';
                echo '<label for="benefits">Benefits: </label>';
                echo '<input type="text" id="benefits" name="benefits" value="' . $row['benefits'] . '" required>';
                echo '</div>';
                echo '<div>';
                echo '<label for="tax">Tax Rate: </label>';
                echo '<input type="tax" id="tax" name="tax" value="' . $row['tax'] . '" required>';
                echo '</div>';
                echo '<input type="hidden" name="ssn" value="' . $row['ssn'] . '">';
                echo '<button type="submit" class="item">Update</button>';
                echo '</form>';
            }
        }
        elseif($ssn_r->num_rows > 0){
            $ssn = $ssn_r->fetch_assoc();
            echo '<form action="update_pay_info.php" method="POST">';
                echo '<label for="employee_info" class="item">Update Employee Pay</label>';
                echo '<div>';
                echo '<label for="salary">Salary: </label>';
                echo '<input type="text" id="salary" name="salary" value="" required>';
                echo '</div>';
                echo '<div>';
                echo '<label for="bonus">Bonus: </label>';
                echo '<input type="text" id="bonus" name="bonus" value="" required>';
                echo '</div>';
                echo '<div>';
                echo '<label for="benefits">Benefits: </label>';
                echo '<input type="text" id="benefits" name="benefits" value="" required>';
                echo '</div>';
                echo '<div>';
                echo '<label for="tax">Tax Rate: </label>';
                echo '<input type="tax" id="tax" name="tax" value="" required>';
                echo '</div>';
                echo '<input type="hidden" name="ssn" value="' . $ssn['ssn'] . '">';
                echo '<button type="submit" class="item">Update</button>';
                echo '</form>';
        }
        else{
            echo "Employee with the SSN not found.";
        }
    ?>
</body>
</html>
