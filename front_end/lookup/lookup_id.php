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

// Get the employees' information
$sql = "SELECT 'hr' AS role, hr.hr_Ssn AS ssn, hr.F_name AS fname, hr.L_name AS lname, hr.email AS email, hr.phone_no AS phone
        FROM employs 
        JOIN hr ON employs.E_Ssn = hr.hr_Ssn WHERE employs.C_name = '".$_SESSION['company_name']."'
        UNION
        SELECT 'accountant' AS role, accountant.acc_Ssn AS ssn, accountant.F_name AS fname, accountant.L_name AS lname, accountant.email AS email, accountant.phone_no AS phone
        FROM employs 
        JOIN accountant ON employs.E_Ssn = accountant.acc_Ssn WHERE employs.C_name = '".$_SESSION['company_name']."'
        UNION
        SELECT 'associate' AS role, associate.acc_Ssn AS ssn, associate.F_name AS fname, associate.L_name AS lname, associate.email AS email, associate.phone_no AS phone
        FROM employs 
        JOIN associate ON employs.E_Ssn = associate.acc_Ssn WHERE employs.C_name = '".$_SESSION['company_name']."'
        ORDER BY fname, lname";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wage Wizards</title>
    <!-- Link to the CSS file -->
    <link rel="stylesheet" href="lookup.css">
</head>
<body>

    <h1 class="lookup-table-heading">List of Employees</h1>
    <button class="back-btn" onclick="window.history.back()">Back</button>

    <div class="lookup-table-container">
    <?php
    // Check if the query returns any results
    if ($result->num_rows > 0) {
        // Start the HTML table
        echo "<table class='lookup-table'><thead><tr><th>SSN</th><th>Role</th><th>Name</th><th>Email</th><th>Phone Number</th></tr></thead><tbody>";

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["ssn"] . "</td><td>" . $row["role"] . "</td><td>" . $row["fname"] . " " . $row["lname"] . "</td><td>" . $row["email"] . "</td><td>" . $row["phone"] . "</td></tr>";
        }

        // End the table
        echo "</tbody></table>";
    } else {
        echo "<p>No users found.</p>";
    }

    // Close the database connection
    $conn->close();
    ?>
    </div>

</body>
</html>