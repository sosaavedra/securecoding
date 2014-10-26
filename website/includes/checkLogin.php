<?php

session_start();

// define variables and set to empty values
$unameErr = "";
$passErr = "";
$pageErr = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {

    require_once "includes/config.php";
    require_once "classes/mysqliconn.php";

    $mysqli = new MysqliConn();
    $mysqli->connect();

    $formValid = true;
    
    if (empty ( $_POST['username'] )) {
        $unameErr = "User name is required";
        $formValid = false;
    }
    
    if (empty ( $_POST['password'] )) {
        $passErr = "Password is required";
        $formValid = false;
    }

    if ($formValid) {
        // escape variables for security
        $username = $mysqli->escape($_POST['username']);
        $password = $mysqli->escape($_POST['password']);
        $hashedPW = hash('sha256', $password);
        
        // check for correct username password
        if(isset($_POST['employee']) && $_POST['employee'] == 1){
            $employee = $mysqli->employeeLogin($username, $hashedPW);
            $mysqli->close();

            if($employee){
                $_SESSION['user_type'] = "employee";
                $_SESSION['logged_user'] = $employee;

                header ( 'Location: empApproveTrans.php' );
            } else {
                $pageErr = "Invalid email/password";
            }
        } else {
            $client = $mysqli->clientLogin($username, $hashedPW);
            $mysqli->close();

            if($client){
                $_SESSION['user_type'] = "client";
                $_SESSION['logged_user'] = $client;

                header ( 'Location: customerPage.php');
            } else {
                $pageErr = "Invalid email/password";
            }
        }
    } 
}
?>
