<?php

	// user_type is set by checkSession.php, no need to check
	
	if(strcmp ( $_SESSION['user_type'], "client") != 0){
		header ( "Location: index.html" );
	}
	

?>
