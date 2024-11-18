<!DOCTYPE html>
<html>
<head lang = "en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Wage Wizards</title>
</head>
<body>
    <h1 class="center">Automatic Salaray by Wage Wizards</h1>
    <p>Company Name</p>
    <p>company Address</p>
    <p>First Name Last Name</p>
    <p>user information</p>
    <p>Expected Salary</p>
    <p>$(salary + bonus + benifits) * (100 - tax rate)</p>
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

        $pay_q = "SELECT pay_details.salary AS salary,
            pay_deltails.bonus AS bonus,
            pay_details.benefits as benefits
            pay_details.bonus AS bonus
            pay_details.tax_rate AS tax FROM gets
            JOIN pay_delails ON gets.pay_id = pay_deltails.id
            WHERE gets.E_Ssn = '".$_SESSION['ssn']."'";

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

        echo $_SESSION['company_name'] . "<br><br>$address<br><br>$fname $lname<br>$email<br>$phone<br><br>";
        echo "Salary:<br>";
        echo "$($salary + $bonus + $benefits) * (100 - $tax)";

        $conn->close();
    ?>
    <form action="edit_self.php" method="POST">
        <button type="submit">Edit information</button>
    </form>
</body>
</html> 