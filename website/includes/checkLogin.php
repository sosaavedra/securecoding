<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {

    require_once "includes/config.php";
    require_once "classes/mysqliconn.php";

    $mysqli = new MysqliConn();
    $mysqli->connect();

    // define variables and set to empty values
    $unameErr = "";
    $passErr = "";
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
        
        // check for correct username password
        if(isset($_POST['employee']) && $_POST['employee'] == 1){
            $mysqli->employeeLogin($username, $password);
            $mysqli->close();

            header ( 'Location: customerPage.html' );
        } else {
            $mysqli->clientLogin($username, $password);
            $mysqli->close();

            header ( 'Location: customerPage.html' );
        }


    } 
}
?>
