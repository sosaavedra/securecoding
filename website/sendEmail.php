<?php

/**
 * This function will send transatcion codes via email to the passed customer id.
 * 
 * @param
 *            customer id $customerId
 */
function sendNewCustEMail($accountNumber) {
    
    require_once "includes/utils.php";
    require_once "includes/config.php";
    require_once "includes/emailConfig.php";
    require_once "classes/mysqliconn.php";
    require_once "swiftmailer/lib/swift_required.php";
    
    $mysqli = new MysqliConn ();
    $mysqli->connect ();
    
    $result = $mysqli->getAccountDetails ( $accountNumber );

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
</body>
</html>
';
            
            $message = Swift_Message::newInstance (EMAIL_HEADER)
            
            ->setFrom ( array ( SYSTEM_EMAIL_ID => EMAIL_FROM) )
            ->setTo ( array ( $row ["email"] ) )
            ->setBody ( $emailBody, 'text/html' );

            if($row['use_scs'] == 'N'){

                $clientName = $row['description'] . " " . $row['first_name'] . " " . $row['last_name'];
                
                $result = $mysqli->getClientTransationNumbers ( $row['id'] );
        
                $filename = createPDF($clientName, $accountNumber, $row['email'], $result);
       
    
                $message->attach(Swift_Attachment::fromPath($filename));
            }
            
            // echo $emailBody;

            $mailResult = $mailer->send ( $message );
    }

    $mysqli->close ();
}


/**
 * This function will send temporary password reset token to customer
 *
 * @param
 *            email id $email
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
