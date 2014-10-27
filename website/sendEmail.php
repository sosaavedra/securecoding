<?php
//sendNewCustEMail ( 1 );

/**
 * This function will send transatcion codes via email to the passed customer id.
 * @param customer id $customerId
 */
function sendNewCustEMail($customerId) {
	
	// code for connecting to DB and fetching transaction codes for a customer
	$con = mysqli_connect ( "localhost", "root", "samurai", "banksys" );
	// Check connection
	if (mysqli_connect_errno ()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error ();
	}
	
	$checkSql = "SELECT * FROM tan_code WHERE client_id = '{$customerId}'";
	$result = mysqli_query ( $con, $checkSql );
	
	if (! $result) {
		die ( 'Error: ' . mysqli_error ( $con ) );
	}
	
	// make email body with codes
	$counter = 1;
	$mailBody = "";
	while ( $row = mysqli_fetch_array ( $result ) ) {
		$mailBody .= "<tr><td>" . $counter . "</td><td>" . $row ['code'] . "</td></tr>";
		$counter ++;
	}
	
	mysqli_close ( $con );
	
	// code to send email
	
	require_once '/home/samurai/Praveer/misc/vendor/swiftmailer/swiftmailer/lib/swift_required.php';
	
	$transport = Swift_SmtpTransport::newInstance ( 'smtp.gmail.com', 465, "ssl" )->setUsername ( 'scteam17@gmail.com' )->setPassword ( 'samurai17' );
	
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
	
	$message = Swift_Message::newInstance ( 'IMP:CONFIDENTIAL: your transation codes' )
	
	->setFrom ( array ('scteam17@gmail.com' => 'Secure Banking' ) )
	->setTo ( array ('raipraveer@gmail.com'	) )
	->setBody ( $emailBody, 'text/html' );
	
	// echo $emailBody;
	
	$result = $mailer->send ( $message );
}

?>