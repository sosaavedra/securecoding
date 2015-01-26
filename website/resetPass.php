<?php 
require_once "includes/checkOrigin.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Reset Password</title>
<meta charset="utf-8">
<link rel="stylesheet" href="css/reset.css" type="text/css" media="all">
<link rel="stylesheet" href="css/layout.css" type="text/css" media="all">
<link rel="stylesheet" href="css/style.css" type="text/css" media="all">
<script type="text/javascript" src="js/jquery-1.4.2.js" ></script>
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/cufon-replace.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_400.font.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_700.font.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_600.font.js"></script>
<!--[if lt IE 9]>
    <script type="text/javascript" src="http://info.template-help.com/files/ie6_warning/ie6_script_other.js"></script>
    <script type="text/javascript" src="js/html5.js"></script>
<![endif]-->
</head>
<body id="page4">


<?php

// define variables for form values validation and set to empty values
$passErr = $cpassErr = $passMatchErr = $tokenErr = $genError = "";
$formValid = true;


require_once "includes/config.php";
require_once "classes/mysqliconn.php";

// checking if request is posted
if ($_POST) {
	
    
    if (empty ( $_POST ['token'] )) {
        $tokenErr = "Token cannot be empty";
        $formValid = false;
    }
   
    if (empty ( $_POST ['password'] ) || strlen ( $_POST ['password'] ) < 6) {
        $passErr = "Password must be atleast 6 char";
        $formValid = false;
    }
    
    if (empty ( $_POST ['email'] ) || ! filter_var ( $_POST ['email'], FILTER_VALIDATE_EMAIL )) {
    	$genError = "Some error occured!";
    	$formValid = false;
    }
    
    if (empty ( $_POST ['cpassword'] )) {
        $cpassErr = "Retype password is required";
        $formValid = false;
    } else if (!($_POST ['password'] === $_POST ['cpassword'])) {
        $passMatchErr = "Passwords do not match";
        $formValid = false;
    }
    
    
    if ($formValid) {
    	
    	$mysqli = new MysqliConn ();
    	$mysqli->connect ();
    	
        // escape variables for security
        $email = $mysqli->escape ( $_POST ['email'] );
        $token = $mysqli->escape ( $_POST ['token'] );
        $password = $mysqli->escape ( $_POST ['password'] );
        $cpassword = $mysqli->escape ( $_POST ['cpassword'] );
        
        $hashedPW = hash ( 'sha256', $password );
        $mysqli->resetPassword ($email, $token, $hashedPW);
        header ( 'Location: passwordSuccess.php' );
        $mysqli->close ();
    }
}

?>
<div class="main">
<!-- header -->
    <header>
        <div class="wrapper">
            <a href="index.php" id="logo">BankSys</a>
        </div>
    </header>
<!-- / header -->
<!-- content -->
    <section id="content">
        <div class="wrapper">
            <div class="pad">
                <div class="wrapper">
                    <article class="col1"><h2>Reset Password</h2></article>
                    <article class="col2 pad_left1"><h2>Contact us</h2></article>
                </div>
            </div>
            <div class="box pad_bot1">
                <div class="pad marg_top">
                    <article class="col1">
                        <form id="login" class="formstyle" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"].'?email='.$_GET ['email']);?>" method="post">
                            <div>
                             <p> We sent you an email with token, Please enter the token below </p>
                                <div class="wrapper">
                                 <div class="wrapper">
                                    <div class="bg"><input class="input" autocomplete="off" type="text" name="token" id="token"></div>Token:
                                </div>
                                <span class="error"><?php echo $tokenErr;?></span>
                                <div class="wrapper">
                                    <div class="bg"><input class="input" autocomplete="off" type="password" name="password" id="password"></div>Password:
                                </div>
                                <span class="error"><?php echo $passErr;?></span>
                                <div class="wrapper">
                                    <div class="bg"><input class="input" autocomplete="off" type="password" name="cpassword" id="cpassword"></div>Re-Password:
                                </div>
                                <span class="error"><?php echo $cpassErr.$passMatchErr;?></span>
                                </div> <span class="error"> <?php echo $genError;?> </span>
                                <div class="wrapper">
                                    <div style="margin-right: 100px">
                                    <input class='button' type='submit' name='reset' value='Reset' id='reset'>
                                    <input type='hidden' name='email' value='<?php if (isset($_GET ['email'])) echo htmlspecialchars($_GET ['email']);?>' id='email'>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </article>
                    <article class="col2 pad_left1">
                        <div class="wrapper">
                            <p class="cols pad_bot3">
                                <strong>
                                    Country:<br>
                                    City:<br>
                                    Telephone:<br>
                                    Email:
                                </strong>
                            </p>
                            <p class="pad_bot3">
                                Germany<br>
                                Munich<br>
                                +49 1234567890<br>
                                <a href="mailto:">scteam17@gmail.com</a>
                            </p>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>
<!-- / content -->
</div>
</body>
</html>
