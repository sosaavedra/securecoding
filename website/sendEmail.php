<?php

/**
 * This function will send transatcion codes via email to the passed customer id.
 * 
 * @param
 *        	customer id $customerId
 */
function sendNewCustEMail($customerId) {
	
	require_once "includes/config.php";
	require_once "includes/emailConfig.php";
	require_once "classes/mysqliconn.php";
	require_once "swiftmailer/lib/swift_required.php";
	
	$mysqli = new MysqliConn ();
	$mysqli->connect ();
	
	$result = $mysqli->getClientTransationNumbers ( $customerId );
	
	if (! $result) {
		die ( 'Error:in fetching transation codes' );
	}
	
	// make email body with codes
	$counter = 1;
	$mailBody = "";
	while ( $row = mysqli_fetch_array ( $result ) ) {
		$mailBody .= "<tr><td>" . $counter . "</td><td>" . $row ['code'] . "</td></tr>";
		$counter ++;
	}
	
	// get customer email address
	
	$result = $mysqli->getClientDetails ( $customerId );
	$mysqli->close ();
	if (! $result) {
		die ( 'Error:in fetching customer details' );
	}
	
	if (! empty ( $result ) && $result->num_rows > 0) {
		
		$row = $result->fetch_assoc ();
			
			// code to send email
			
			$transport = Swift_SmtpTransport::newInstance (EMAIL_SMTP, EMAIL_PORT, "ssl" )->setUsername (SYSTEM_EMAIL_ID)->setPassword (EMAIL_PASSWORD);
			
			$mailer = Swift_Mailer::newInstance ( $transport );
			
			$emailBody = '
<html>
<head>
  <title>Your transaction codes</title>
</head>
<body>
  <p>Thank you for registering! We have given you 50.000 euros balance as gift!
  Below are your secure transaction codes, Please keep them secret</p>
  <table border="1">
    <tr>
      <th>Serial number</th><th>Transaction code</th>
    </tr>
    <tr>' . $mailBody . '</tr>
  </table>
</body>
</html>
';
			
			$message = Swift_Message::newInstance (EMAIL_HEADER)
			
			->setFrom ( array ( SYSTEM_EMAIL_ID => EMAIL_FROM) )
			->setTo ( array ( $row ["email"] ) )
			->setBody ( $emailBody, 'text/html' );
			
			// echo $emailBody;
			
			$result = $mailer->send ( $message );
	}
}


/**
 * This function will send temporary password reset token to customer
 *
 * @param
 *        	email id $email
 */
function sendTokenEMail($email) {

	require_once "includes/config.php";
	require_once "includes/emailConfig.php";
	require_once "classes/mysqliconn.php";
	require_once "swiftmailer/lib/swift_required.php";

	$mysqli = new MysqliConn ();
	$mysqli->connect ();

	$result = $mysqli->getClientPaswordToken ( $email );

	if (! $result) {
		die ( 'Error:in fetching token' );
	}

	if (! empty ( $result ) && $result->num_rows > 0) {

		$row = $result->fetch_assoc ();
			
		// code to send email
			
		$transport = Swift_SmtpTransport::newInstance (EMAIL_SMTP, EMAIL_PORT, "ssl" )->setUsername (SYSTEM_EMAIL_ID)->setPassword (EMAIL_PASSWORD);
			
		$mailer = Swift_Mailer::newInstance ( $transport );
			
		$emailBody = '
<html>
<head>
  <title>Your temporary password reset token</title>
</head>
	<body>
	    <p>Someone is trying to reset your password.</p>
		<p>If it was you the token generated for you is'.$row ["token"].'</p>
		<p>IF it was not you, ignore this email.</p>
	</body>
</html>';
		
		$message = Swift_Message::newInstance (EMAIL_HEADER)
			
		->setFrom ( array ( SYSTEM_EMAIL_ID => EMAIL_FROM) )
		->setTo ( array ( $row ["email"] ) )
		->setBody ( $emailBody, 'text/html' );
			
		// echo $emailBody;
			
		$result = $mailer->send ( $message );
	}
}

?>