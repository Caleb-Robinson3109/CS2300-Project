<?php
session_start();

$db_servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "hr_database";
//creates the connection
$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//gets the password and username from login.html
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //gets data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    //do sql quary to see if the username and password are in the db
    $sql = "SELECT login.username AS username, login.password AS password, employs.E_Ssn AS ssn FROM login JOIN has ON login.username = has.username JOIN employs ON has.E_Ssn = employs.E_Ssn WHERE login.username = '$username' AND employs.C_name = '".$_SESSION['company_name']."'";
    $result = $conn->query($sql);

    //there is username and passwrod combo in the db
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if($row['password'] == $password)
        {
            $_SESSION['username'] = $username;
            $_SESSION['ssn'] = $row['ssn'];

            if($role == "hr"){
                $sql_hr = "SELECT * FROM hr WHERE hr_Ssn = '".$_SESSION['ssn']."'";
                $result_hr = $conn->query($sql_hr);
            
                if($result_hr->num_rows > 0)
                {
                    header("Location: ../hr_home/hr_home.html");
                    exit();
                }
                else{
                    echo "<script type='text/javascript'>
                            alert('You do not have the HR role.');
                            window.history.back();
                          </script>";
                    exit();
                }
            }
            elseif($role == "accountant"){
                $sql_acc = "SELECT * FROM accountant WHERE acc_Ssn = '".$_SESSION['ssn']."'";
                $sql_hr = "SELECT * FROM hr WHERE hr_Ssn = '".$_SESSION['ssn']."'";
                $result_acc = $conn->query($sql_acc);
                $result_hr = $conn->query($sql_hr);
            
                if($result_acc->num_rows > 0)
                {
                    header("Location: ../hr_home/hr_home.html");
                    exit();
                }
                elseif($result_hr->num_rows > 0)
                {
                    header("Location: ../account_home/account_home.html");
                    exit();
                }
                else{
                    echo "<script type='text/javascript'>
                            alert('You do not have the Accountant role.');
                            window.history.back();
                          </script>";
                          exit();
                }
            }
            else{
                header("Location: ../assoc_home/assoc_home.php");
                exit();
            }
        }
        else{
            echo "<script type='text/javascript'>
                alert('Username and password not found.');
                window.history.back();
              </script>";
              exit();
        }
    }
    else{
        echo "<script type='text/javascript'>
                alert('Username and password not found.');
                window.history.back();
              </script>";
              exit();
    }
}

// Close the database connection
$conn->close();