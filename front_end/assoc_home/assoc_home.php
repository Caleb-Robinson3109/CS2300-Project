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
$address_q = "SELECT Address FROM company WHERE Name = '".$_SESSION['company_name']."'";
$address_r = $conn->query($address_q);
if ($address_r === FALSE) {
    // If there was an error, output the SQL query and the error message
    echo "Error with query: " . $address_q . "<br>";
    echo "MySQL error: " . $conn->error;
}
$address_row = $address_r->fetch_assoc();
$address = $address_row['Address'];

$sql = "SELECT hr.hr_Ssn AS ssn, 'hr' AS role, hr.F_name AS fname, hr.L_name AS lname, hr.email AS email, hr.phone_no AS phone, login.username AS username, login.password AS password
    FROM hr
    JOIN has ON hr.hr_Ssn = has.E_Ssn
    JOIN login ON has.username = login.username
    WHERE hr.hr_Ssn = '".$_SESSION['ssn']."'
    UNION
    SELECT accountant.acc_Ssn AS ssn, 'accountant' AS role, accountant.F_name AS fname, accountant.L_name AS lname, accountant.email AS email, accountant.phone_no AS phone, login.username AS username, login.password AS password
    FROM accountant
    JOIN has ON accountant.acc_Ssn = has.E_Ssn
    JOIN login ON has.username = login.username
    WHERE accountant.acc_Ssn = '".$_SESSION['ssn']."'
    UNION
    SELECT associate.acc_Ssn AS ssn, 'associate' AS role, associate.F_name AS fname, associate.L_name AS lname, associate.email AS email, associate.phone_no AS phone, login.username AS username, login.password AS password
    FROM associate
    JOIN has ON associate.acc_Ssn = has.E_Ssn
    JOIN login ON has.username = login.username
    WHERE associate.acc_Ssn = '".$_SESSION['ssn']."'";

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
        gets.E_Ssn = '" . $_SESSION['ssn'] . "'
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

$bank_q = "SELECT pay.acc_num AS acc, pay.rout_num AS rout FROM pay WHERE id = '" . $_SESSION['ssn'] . "'";
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
        $dummy_r = $conn->query("SELECT '' AS acc, '' AS rout FROM employee WHERE E_Ssn = '" . $_SESSION['ssn'] . "'");
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
    <title>Associate Home</title>
    <link rel="stylesheet" href="associate.css">
</head>
<body>
    <!-- Page Header -->
    <div class="header">
        <h1>Associate Page</h1>
    </div>

    <!-- Associate Section -->
    <div class="main-container">
        <div class="form-container">
            <form >
            <div class="form-group">
            <h1>Employee</h1>
            <label for="company">Company</label>
            <input type="text" id="company" value="<?php echo $_SESSION['company_name']; ?>" readonly>
            </div>
            <div class="form-group">
            <label for="address">Company Address</label>
            <input type="text" id="address" value="<?php echo $address; ?>" readonly>
            </div>  
            <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" value="<?php echo $username; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" id="password" value="<?php echo $password; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="ssn">Social Security Number</label>
                <input type="text" id="ssn" value="<?php echo $ssn; ?>" readonly>
            </div>
                
            </form>
        </div>

        <!-- Pay Section -->
        <div class="form-container">
            <h1>Pay</h1>
            <form class="form">
                <div class="form-group">
                    <label for="monthly-salary">Salary</label>
                    <input type="text" id="monthly-salary" value="<?php echo $salary; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="benefits">Benefits</label>
                    <input id="benefits" value="<?php echo $benefits; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="bonus">Bonus</label>
                    <input id="bonus" value="<?php echo $bonus; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="total">Total</label>
                    <input id="total" value="<?php 
                    $total = $salary + $bonus + $benefits;
                    echo $total; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="tax">Tax Rate</label>
                    <input id="tax" value="<?php echo $tax; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="post">Post Tax Income</label>
                    <input id="post" value="<?php 
                    $net = $salary + $bonus + $benefits;
                    $tax_rate = (100 - $tax) / 100;
                    $take_home = $net * $tax_rate;
                    echo $take_home; ?>" readonly>
                </div>
            </form>
        </div>

        <!-- Bank Section -->
        <div class="form-container bank">
            <h1>Bank</h1>
            <form class="form" action="update_self.php" method="post">
                <div class="form-group">
                    <label for="acc">Account Number *</label>
                    <input type="text" id="acc" name="acc" value="<?php echo $acc; ?>">
                </div>
                <div class="form-group">
                    <label for="rout">Routing Number *</label>
                    <input type="text" id="rout" name="rout" value="<?php echo $rout; ?>">
                </div>
                <?php
                    echo "<input type=\"hidden\" name=\"curr_role\" value=\"$role\">";
                    echo "<input type=\"hidden\" name=\"fname\" value=\"$fname\">";
                    echo "<input type=\"hidden\" name=\"lname\" value=\"$lname\">";
                    echo "<input type=\"hidden\" name=\"email\" value=\"$email\">";
                    echo "<input type=\"hidden\" name=\"phone\" value=\"$phone\">";                    
                ?>
                <button type="submit" class="btn edit-btn">Edit</button>
            </form>
        </div>

        <!-- Personal Section -->
        <div class="form-container personal">
            <h1>Personal</h1>
            <form class="form" action="update_self.php" method="post">
                <div class="form-group">
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" name="fname" value="<?php echo $fname; ?>">
                </div>
                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" name="lname" value="<?php echo $lname; ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" value="<?php echo $email; ?>">
                </div>
                    <?php
                        echo "<input type=\"hidden\" name=\"curr_role\" value=\"$role\">";
                        echo "<input type=\"hidden\" name=\"acc\" value=\"$acc\">";
                        echo "<input type=\"hidden\" name=\"rout\" value=\"$rout\">";
                    ?>
                <button type="submit" class="btn edit-btn">Edit</button>
            </form>
        </div>
    </div>
</body>
</html>