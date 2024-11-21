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
}

$sql = "SELECT hr.hr_Ssn AS ssn, 'hr' AS role, hr.F_name AS fname, hr.L_name AS lname, hr.email AS email, hr.phone_no AS phone, login.username AS username, login.password AS password
    FROM hr
    JOIN has ON hr.hr_Ssn = has.E_Ssn
    JOIN login ON has.username = login.username
    WHERE hr.hr_Ssn = '$lookup_ssn'
    UNION
    SELECT accountant.acc_Ssn AS ssn, 'accountant' AS role, accountant.F_name AS fname, accountant.L_name AS lname, accountant.email AS email, accountant.phone_no AS phone, login.username AS username, login.password AS password
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

if ($result === FALSE) {
    // If there was an error, output the SQL query and the error message
    echo "Error with query: " . $sql . "<br>";
    echo "MySQL error: " . $conn->error;
}

$pay_q = "
    SELECT 
        pay_details.salary AS salary,
        pay_details.bonus AS bonus,
        pay_details.benefits AS benefits,
        pay_details.tax_rate AS tax
    FROM 
        gets
    JOIN 
        pay_details ON gets.pay_id = pay_details.pay_id
    WHERE 
        gets.E_Ssn = '$lookup_ssn'
    ";

$pay_r = $conn->query($pay_q);
if ($pay_r === FALSE) {
    // If there was an error, output the SQL query and the error message
    echo "Error with query: " . $sql . "<br>";
    echo "MySQL error: " . $conn->error;
}
$pay_row = $pay_r->fetch_assoc();
$salary = $pay_row['salary'];
$bonus = $pay_row['bonus'];
$benefits = $pay_row['benefits'];
$tax = $pay_row['tax'];

$row = $result->fetch_assoc();
$fname = $row['fname'];
$lname = $row['lname'];
$phone = $row['phone'];
$email = $row['email'];
$role = $row['role'];
$ssn = $row['ssn'];
$username = $row['username'];
$password = $row['password'];
$bank_q = "SELECT pay.acc_num AS acc, pay.rout_num AS rout FROM pay WHERE id = '$lookup_ssn'";
$bank_r = $conn->query($bank_q);
$bank = null;
$acc = null;
$rout = null;
if($bank_r->num_rows > 0){
    $bank = $bank_r->fetch_assoc();
    $acc = $bank['acc'];
    $rout = $bank['rout'];
}
else{
    $dummy_r = $conn->query("SELECT '' AS acc, '' AS rout FROM employee WHERE E_Ssn = '$lookup_ssn'");
    $bank = $dummy_r->fetch_assoc();
    $acc = $bank['acc'];
    $rout = $bank['rout'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Updated Layout</title>
    <link rel="stylesheet" href="edit_pay.css">
</head>
<body>
    <div class="main-container">
        <!-- Associate Section -->
        <div class="form-container associate">
            <h1>Employee</h1>
            <form class="form">
                <div class="form-group">
                    <label for="ssn">Social Security Number</label>
                    <input type="text" id="ssn" value="<?php echo $ssn; ?>" readonly>
                </div>    
                <div class="form-group">
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" value="<?php echo $fname; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" value="<?php echo $lname; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="cemail">Email</label>
                    <input type="email" id="email" value="<?php echo $email; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="phone" id="phone" value="<?php echo $phone; ?>" readonly>
                </div>
            </form>
        </div>

        <!-- Pay Section -->
        <div class="form-container pay">
            <h1>Pay</h1>
            <form class="form" action="update_pay_info.php" method="post">
                <div class="form-group">
                    <label for="monthly-salary">Salary</label>
                    <input type="text" id="monthly-salary" name="salary" value="<?php echo $salary; ?>">
                </div>
                <div class="form-group">
                    <label for="benefits">Benefits</label>
                    <input id="benefits" name="benefits" value="<?php echo $benefits; ?>">
                </div>
                <div class="form-group">
                    <label for="bonus">Bonus</label>
                    <input id="bonus" name="bonus" value="<?php echo $bonus; ?>">
                </div>
                <div class="form-group">
                    <label for="tax">Tax Rate</label>
                    <input id="tax" name="tax" value="<?php echo $tax; ?>">
                </div>
                <?php
                    echo "<input type=\"hidden\" name=\"ssn\" value=\"$ssn\">";
                ?>
                <button type="submit" class="btn edit-btn">Edit</button>
            </form>
        </div>

        <!-- Bank Section -->
        <div class="form-container bank">
            <h1>Bank</h1>
            <form class="form">
                <div class="form-group">
                    <label for="account-number">Account Number *</label>
                    <input type="text" id="account-number" value="<?php echo $acc; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="routing-number">Routing Number *</label>
                    <input type="text" id="routing-number" value="<?php echo $rout; ?>" readonly>
                </div>
            </form>
        </div>
    </div>

</body>
</html>