<!DOCTYPE html>
<html lang="en">
<head>
<title>Registration</title>
<meta charset="utf-8">
<link rel="stylesheet" href="css/reset.css" type="text/css" media="all">
<link rel="stylesheet" href="css/layout.css" type="text/css" media="all">
<link rel="stylesheet" href="css/style.css" type="text/css" media="all">
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
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
$fnameErr = $lnameErr = $emailErr = $passErr = $cpassErr = $passMatchErr = $titleErr = $tanOptionErr = "";
$formValid = true;
$alreadyRegisteredErr = "";

require_once "includes/config.php";
require_once "classes/mysqliconn.php";

$mysqli = new MysqliConn ();
$mysqli->connect ();

// for displaying titles

$titleTypes = $mysqli->getTitleTypes ();

$mysqli->close ();

// checking if request is posted
if ($_POST) {
    
    if (empty ( $_POST ['title'] )) {
        $titleErr = "Title not valid";
        $formValid = false;
    }
    
    if (empty ( $_POST ['firstname'] ) || ! preg_match ( "/^[a-zA-Z ]*$/", $_POST ['firstname'] )) {
        $fnameErr = "First name can contain only alphabets";
        $formValid = false;
    }
    
    if (empty ( $_POST ['lastname'] ) || ! preg_match ( "/^[a-zA-Z ]*$/", $_POST ['lastname'] )) {
        $lnameErr = "Last name can contain only alphabets";
        $formValid = false;
    }
    
    if (empty ( $_POST ['email'] ) || ! filter_var ( $_POST ['email'], FILTER_VALIDATE_EMAIL )) {
        $emailErr = "Email is not valid";
        $formValid = false;
    }
    
    if (empty ( $_POST ['password'] ) || strlen ( $_POST ['password'] ) < 6) {
        $passErr = "Password must be atleast 6 char";
        $formValid = false;
    }
    
    if (empty ( $_POST ['cpassword'] )) {
        $cpassErr = "Retype password is required";
        $formValid = false;
    } else if (!($_POST ['password'] === $_POST ['cpassword'])) {
        $passMatchErr = "Passwords do not match";
        $formValid = false;
    }
    
    if (empty ( $_POST ['tanOption'] )) {
    	$tanOptionErr = "Please select a TAN option";
    	$formValid = false;
    }
    
    
    if ($formValid) {
        // escape variables for security
        $title = $mysqli->escape ( $_POST ['title'] );
        $firstname = $mysqli->escape ( $_POST ['firstname'] );
        $lastname = $mysqli->escape ( $_POST ['lastname'] );
        $email = $mysqli->escape ( $_POST ['email'] );
        $password = $mysqli->escape ( $_POST ['password'] );
        $cpassword = $mysqli->escape ( $_POST ['cpassword'] );
        $tanOption = $mysqli->escape ( $_POST ['tanOption'] );
        
        $scsOpt = 'N';
        if($tanOption === "scs"){
        	$scsOpt = 'Y';
        }
        
        $hashedPW = hash ( 'sha256', $password );
        
        if ($mysqli->createClient ( $title, $firstname, $lastname, $email, $hashedPW, $scsOpt )) {
            header ( 'Location: registerSuccess.html' );
        } else {
            $alreadyRegisteredErr = "Email id already registered!";
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
                    <li id="menu_active"><a href="registration.php"><span><span>Register</span></span></a></li>
                    <li><a href="login.php"><span><span>Login</span></span> </a></li>
                    <li class="omega"><a href="forgetPass.php"><span><span>Forgot password?</span></span> </a></li>
                </ul>
            </nav>
        </header>
        <!-- / header -->
        <!-- content -->
        <section id="content">
            <div class="wrapper">
                <div class="pad">
                    <div class="wrapper">
                        <article class="col1">
                            <h2>New User</h2>
                        </article>
                        <article class="col2 pad_left1">
                            <h2>Contact Us</h2>
                        </article>
                    </div>
                </div>
                <div class="box pad_bot1">
                    <div class="pad marg_top">
                        <article class="col1">
                            <form id="registration" class="formstyle" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                <div>
                                    <div class="wrapper">
                                        <div>
                                            <select name="title" id="title" class="bg">
                                    <?php
                                        while ( $row = $titleTypes->fetch_assoc () ) {
                                            echo "<option value='" . $row ["id"] . "'>" . $row ["description"] . "</option>";
                                        }
                                    ?>
                                    </select>
                                        </div>
                                        Select Type:
                                    </div>
                                    <span class="error"><?php echo $titleErr;?></span>
                                    <div class="wrapper">
                                        <div class="bg">
                                            <input class="input" type="text" name="firstname" id="firstname" value="<?php if(isset($_POST ['firstname'])) echo $_POST ['firstname']; ?>">
                                        </div>
                                        First Name:
                                    </div>
                                    <span class="error"><?php echo $fnameErr;?></span>
                                    <div class="wrapper">
                                        <div class="bg">
                                            <input class="input" type="text" name="lastname" id="lastname" value="<?php if(isset($_POST ['lastname']))echo $_POST ['lastname']; ?>">
                                        </div>
                                        Last Name:
                                    </div>
                                    <span class="error"><?php echo $lnameErr;?></span>
                                    <div class="wrapper">
                                        <div class="bg">
                                            <input class="input" type="text" name="email" id="email" value="<?php if(isset($_POST ['email']))echo $_POST ['email']; ?>">
                                        </div>
                                        Email:
                                    </div>
                                    <span class="error"><?php echo $emailErr;?></span>
                                     <span class="error"><?php echo $alreadyRegisteredErr;?></span>
                                    <div class="wrapper">
                                        <div class="bg">
                                            <input class="input" autocomplete="off" type="password" name="password" id="password">
                                        </div>
                                        Password:
                                    </div>
                                    <span class="error"><?php echo $passErr;?></span>
                                    <div class="wrapper">
                                        <div class="bg">
                                            <input class="input" autocomplete="off" type="password" name="cpassword" id="cpassword">
                                        </div>
                                        Retype Password:
                                    </div>
                                    <span class="error"><?php echo $cpassErr.$passMatchErr;?></span>
                                    
                                    <div class="wrapper">
										<div class="bg">
											<input type="radio" name="tanOption"
											<?php if (isset($_POST ['tanOption']) && $_POST ['tanOption']==="email") echo "checked";?>
											value="email">Send me TAN via email 
										
										</div>
										TAN option:
									</div>
									
									<div class="wrapper">
										<div class="bg">
											<input type="radio"	name="tanOption"
											<?php if (isset($_POST ['tanOption']) && $_POST ['tanOption']==="scs") echo "checked";?>
											value="scs">Download SCS(smart card simulator)
										
										</div>
									
									</div>
									<span class="error"><?php echo $tanOptionErr;?></span>
									
                                    <div style="margin-right: 100px">
                                        <input class='button' style="width:120px;height:40px;" type='submit' name='register' value='Register' id='register' />
                                    </div>
                                </div>
                            </form>
                        </article>
                        <article class="col2 pad_left1">
                            <div class="wrapper">
                                <p class="cols pad_bot3">
                                    <strong> Country:<br> City:<br> Telephone:<br> Email:
                                    </strong>
                                </p>
                                <p class="pad_bot3">
                                    Germany<br> Munich<br> +49 1234567890<br> <a href="mailto:">scteam17@gmail.com</a>
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
        <!-- / content -->
    </div>
    <script type="text/javascript"> Cufon.now(); </script>
</body>
</html>
