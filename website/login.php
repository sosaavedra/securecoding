 
<?php
$con = mysqli_connect ( "localhost", "root", "samurai", "banksys" );
// Check connection
if (mysqli_connect_errno ()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error ();
}

// escape variables for security
$username = mysqli_real_escape_string ( $con, $_POST ['username'] );
$password = mysqli_real_escape_string ( $con, $_POST ['password'] );

// define variables and set to empty values
$unameErr = $passErr = "";
$formValid = true;

if ($_SERVER ["REQUEST_METHOD"] == "POST") {
    
    if (empty ( $username )) {
        $unameErr = "User name is required";
        $formValid = false;
    }
    
    if (empty ( $password )) {
        $passErr = "Password is required";
        $formValid = false;
    }
    
    if ($formValid) {
        
        // check for correct username password
        $sql = "SELECT * FROM `client` WHERE 'first_name'='$username'";
        
        if (! mysqli_query ( $con, $sql )) {
            die ( 'Error: ' . mysqli_error ( $con ) );
        }
        
        mysqli_close ( $con );
        header ( 'Location: customerPage.html' );
    } 

    else {
        header ( 'Location: login.html' );
    }
}    else {
        header ( 'Location: error.html' );
    }

?>
