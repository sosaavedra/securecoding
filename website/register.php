 
<?php
$con = mysqli_connect ( "localhost", "root", "samurai", "banksys" );
// Check connection
if (mysqli_connect_errno ()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error ();
}

// escape variables for security
$title = mysqli_real_escape_string ( $con, $_POST ['title'] );
$firstname = mysqli_real_escape_string ( $con, $_POST ['firstname'] );
$lastname = mysqli_real_escape_string ( $con, $_POST ['lastname'] );
$email = mysqli_real_escape_string ( $con, $_POST ['email'] );
$password = mysqli_real_escape_string ( $con, $_POST ['password'] );
$cpassword = mysqli_real_escape_string ( $con, $_POST ['cpassword'] );

// define variables and set to empty values
$fnameErr = $lnameErr = $emailErr = $passErr = $cpassErr = $passMatchErr = "";
$formValid = true;

if ($_SERVER ["REQUEST_METHOD"] == "POST") {
	
	if (empty ( $firstname )) {
		$fnameErr = "First name is required";
		$formValid = false;
	}
	
	if (empty ( $lastname )) {
		$lnameErr = "Last name is required";
		$formValid = false;
	}
	
	if (empty ( $email )) {
		$emailErr = "Email is required";
		$formValid = false;
	}
	
	if (empty ( $password )) {
		$passErr = "Password is required";
		$formValid = false;
	}
	
	if (empty ( $cpassword )) {
		$cpassErr = "Retype password is required";
		$formValid = false;
	}
	
	if (strcmp ( $password, $cpassword ) != 0) {
		$passMatchErr = "Passwords do not match";
		$formValid = false;
	}
	
	if ($formValid) {
		
		// insert into client table
		$sql = "INSERT INTO `client` (`first_name`, `last_name`, `email`) VALUES
				('$firstname', '$lastname', '$email')";
		
		if (! mysqli_query ( $con, $sql )) {
			die ( 'Error: ' . mysqli_error ( $con ) );
		}
		
		// insert into user table, user type = 1 for client
		
		$sql = "INSERT INTO `user` (`pwd`, `user_type_id`) VALUES
		('$password', '1')";
		
		if (! mysqli_query ( $con, $sql )) {
			die ( 'Error: ' . mysqli_error ( $con ) );
		}
		
		mysqli_close ( $con );
		header ( 'Location: registerSuccess.html' );
	} 

	else {
		header ( 'Location: registration.html' );
	}
}	else {
		header ( 'Location: error.html' );
	}

?>
