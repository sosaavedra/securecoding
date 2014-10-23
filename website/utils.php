		  
<?php

// this method returns a single randon 15 digit code
function generateRandomTransactionNumber() {
	return substr ( strtoupper ( md5 ( uniqid ( rand (), true ) ) ), 0, 15 );
}

// this method generates 100 random transation codes for a customer
function generateTransactionCodes($customerid) {
	
	// connect to DB
	$con = mysqli_connect ( "localhost", "root", "samurai", "banksys" );
	// Check connection
	if (mysqli_connect_errno ()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error ();
	}
	
	// generate 100 codes
	
	$counter = 0;
	while ( $counter < 10 ) {
		
		// generate random number
		$transNo = generateRandomTransactionNumber ();
		// check that this number should not be in the database
		
		$checkSql = "SELECT * FROM tan_code WHERE code = '{$transNo}'";
		$result = mysqli_query ($con,$checkSql );
		
		if (!$result) {
			die ( 'Error: ' . mysqli_error ( $con ) );
		}
		
		
		
		if (mysqli_num_rows ( $result ) == 0) {
			// code doesnt exist, add into DB and continue
			$sql = "INSERT INTO `tan_code` (`client_id`, `code`, `valid`) VALUES
				('$customerid', '$transNo', 'Y')";
			
			if (! mysqli_query ( $con, $sql )) {
				die ( 'Error: ' . mysqli_error ( $con ) );
			}
			
			echo $transNo;
			
			// increment counter
			$counter ++;
		} else {
			// transation code already exists, try next code
			continue;
		}
	}
	
	mysqli_close ( $con );
}

?>
		
