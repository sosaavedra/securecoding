<?php

require_once "classes/employee.php";
require_once "classes/client.php";


session_start();


if(isset($_SESSION['user_type'])){
    $user = $_SESSION['logged_user'];

    //TODO check again in database
} else {
    header ("Location: index.php");
}

?>
