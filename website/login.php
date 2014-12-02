<?php

include_once "includes/checkLogin.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Login</title>
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
                <li id="menu_active"><a href="login.php"><span><span>Login</span></span> </a></li>
                <li class="omega"><a href="forgetPass.php"><span><span>Forget password</span></span> </a></li>
            </ul>
        </nav>
    </header>
<!-- / header -->
<!-- content -->
    <section id="content">
        <div class="wrapper">
            <div class="pad">
                <div class="wrapper">
                    <article class="col1"><h2>User Login</h2></article>
                    <article class="col2 pad_left1"><h2>Contact us</h2></article>
                </div>
            </div>
            <div class="box pad_bot1">
                <div class="pad marg_top">
                    <article class="col1">
                        <form id="login" class="formstyle" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                            <div>
                                <div class="wrapper">
                                <div class="wrapper">
                                    <div class="bg"><input class="input" type="text" name="username" id="username"></div>E-Mail:
                                </div>
                                <div class="wrapper">
                                    <div class="bg"><input class="input" autocomplete="off" type="password" name="password" id="password"></div>Password:
                                </div>
                                    <div class="bg" style="background: none; border:none; box-shadow: none;">
                                        <input class="input" type="checkbox" name="employee" id="employee" value="1" />I work here!
                                    </div>
                                   
                                </div> <span class="error"><?php echo $pageErr;?></span>
                                <div class="wrapper">
                                    <div style="margin-right: 100px">
                                    <input class='button' type='submit' name='login' value='Login' id='login'>
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
