<!DOCTYPE html>
<html lang="en">
<head>
<title>Forget password</title>
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

require_once "includes/config.php";
require_once "classes/mysqliconn.php";
require_once "sendEmail.php";

// define variables for form values validation and set to empty values
$emailErr = "";
$formValid = true;

// checking if request is posted
if ($_POST) {
    
   
    if (empty ( $_POST ['username'] ) || ! filter_var ( $_POST ['username'], FILTER_VALIDATE_EMAIL )) {
        $emailErr = "Email is not valid";
        $formValid = false;
    }
    
    
    if ($formValid) {
    	
    	$mysqli = new MysqliConn ();
    	$mysqli->connect ();
    	
       		// escape variables for security
        	$email = $mysqli->escape ( $_POST ['username'] );
        	
        	
        	
        	if($mysqli->forgetPassword ($email))
        	{
        		//send email
        		sendTokenEMail($email);
        		header ( 'Location: resetPass.php?email='.$email);
        	}else{
        		$emailErr = "Email does not exist.";
        	}
            $mysqli->close ();
        }
    }

?>

<div class="main">
<!-- header -->
    <header>
        <div class="wrapper">
            <a href="index.html" id="logo">BankSys</a>
        </div>
        <nav>
            <ul id="menu">
                <li class="alpha"><a href="index.html"><span><span>Home</span></span></a></li>
                <li><a href="registration.php"><span><span>Register</span></span></a></li>
                <li><a href="login.php"><span><span>Login</span></span> </a></li>
                <li id="menu_active" class="omega"><a href="forgetPass.php"><span><span>Forget password</span></span> </a></li>
            </ul>
        </nav>
    </header>
<!-- / header -->
<!-- content -->
    <section id="content">
        <div class="wrapper">
            <div class="pad">
                <div class="wrapper">
                    <article class="col1"><h2>Forget password</h2></article>
                    <article class="col2 pad_left1"><h2>Contact us</h2></article>
                </div>
            </div>
            <div class="box pad_bot1">
                <div class="pad marg_top">
                    <article class="col1">
                        <form id="login" class="formstyle" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                         <p>Please type your email, we will send a temporary code to you, which you can use on next page to reset your password.
                            </p>
                            <div>
                               <div class="wrapper">
                                <div class="wrapper">
                                    <div class="bg"><input class="input" type="text" name="username" id="username"></div>E-Mail:
                                </div>
                                </div> <span class="error"><?php echo $emailErr;?></span>
                                <div class="wrapper">
                                    <div style="margin-right: 100px">
                                    <input class='button' type='submit' name='submit' value='Submit' id='submit'>
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
