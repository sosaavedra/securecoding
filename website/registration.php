<!DOCTYPE html>
<html lang="en">
<head>
<title>Registration</title>
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
$fnameErr = $lnameErr = $emailErr = $passErr = $cpassErr = $passMatchErr = $titleErr = "";
$formValid = true;

		// connect to DB
		$con = mysqli_connect ( "localhost", "root", "samurai", "banksys" );
		// Check connection
		if (mysqli_connect_errno ()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error ();
		}
		
		//for displaying titles
		
		$sql = "SELECT * FROM `title_type`"; 
		$titleTypes = mysqli_query ($con, $sql);
		

// checking if request is posted
if ($_POST) {

	if (empty ( $_POST ['title']) ){
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
	
	if (empty ( $_POST ['password'] ) || strlen($_POST ['password']) < 6 ) {
		$passErr = "Password must be atleast 6 char";
		$formValid = false;
	}
	
	if (empty ( $_POST ['cpassword'] )) {
		$cpassErr = "Retype password is required";
		$formValid = false;
	} else 	if (strcmp ( $_POST ['password'], $_POST ['cpassword'] ) != 0) {
		$passMatchErr = "Passwords do not match";
		$formValid = false;
	}
	
	if ($formValid) {
		
		// escape variables for security
		$title = mysqli_real_escape_string ( $con, $_POST ['title'] );
		$firstname = mysqli_real_escape_string ( $con, $_POST ['firstname'] );
		$lastname = mysqli_real_escape_string ( $con, $_POST ['lastname'] );
		$email = mysqli_real_escape_string ( $con, $_POST ['email'] );
		$password = mysqli_real_escape_string ( $con, $_POST ['password'] );
		$cpassword = mysqli_real_escape_string ( $con, $_POST ['cpassword'] );
		
		// insert into client table
		$sql = "INSERT INTO `client` (`title_type_id`, `first_name`, `last_name`, `email`) VALUES
				('$title', '$firstname', '$lastname', '$email')";
		
		if (! mysqli_query ( $con, $sql )) {
			die ( 'Error: ' . mysqli_error ( $con ) );
		}
		
		//get the created client id from database

		$sql = "SELECT id FROM `client` WHERE email='$email'";
		
		$result = mysqli_query ( $con, $sql );
		$person_id = 0;
		
		while ( $row = $result->fetch_assoc () ) {
			$person_id = $row ["id"];
		}
		$hashedPW = hash('sha256', $password);
		
		// insert into user table, user type = 1 for client
		
		$sql = "INSERT INTO `user` (`pwd`, `person_id`, `user_type_id`) VALUES
		('$hashedPW', '$person_id', '1')";
		
		if (! mysqli_query ( $con, $sql )) {
			die ( 'Error: ' . mysqli_error ( $con ) );
		}
		
		header ( 'Location: registerSuccess.html' );
	} 
}

mysqli_close ( $con );

?>
<div class="main">
<!-- header -->
	<header>
		<div class="wrapper">
			<h1><a href="index.html" id="logo">Smart Biz</a></h1>
		</div>
		<nav>
			<ul id="menu">
				<li class="alpha"><a href="index.html"><span><span>Home</span></span></a></li>
				<li id="menu_active"><a href="#"><span><span>Register</span></span></a></li>
				<li><a href="login.php"><span><span>Login</span></span> </a></li>
				<li class="omega"><a href="#"><span><span>Something</span></span></a></li>
			</ul>
		</nav>
	</header>
<!-- / header -->
<!-- content -->
	<section id="content">
		<div class="wrapper">
			<div class="pad">
				<div class="wrapper">
					<article class="col1"><h2>New user registration</h2></article>
					<article class="col2 pad_left1"><h2>Contact us</h2></article>
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
									    echo "<option value='".$row["id"]."'>".$row["description"]."</option>";
									      }
									 ?>
									</select>
								 </div>Select type:
								</div>
								<span class="error"><?php echo $titleErr;?></span>
								<div class="wrapper">
									<div class="bg"><input class="input" type="text" name="firstname" id="firstname" value="<?php echo $_POST ['firstname']; ?>"></div>First name:
								</div>
								<span class="error"><?php echo $fnameErr;?></span>
								<div class="wrapper">
									<div class="bg"><input class="input" type="text" name="lastname" id="lastname" value="<?php echo $_POST ['lastname']; ?>"></div>Last name:
								</div>
								<span class="error"><?php echo $lnameErr;?></span>
								<div class="wrapper">
									<div class="bg"><input class="input" type="text" name="email" id="email" value="<?php echo $_POST ['email']; ?>"></div>Email:
								</div>
								<span class="error"><?php echo $emailErr;?></span>
								<div class="wrapper">
									<div class="bg"><input class="input" type="password" name="password" id="password" ></div>Password:
								</div>
								<span class="error"><?php echo $passErr;?></span>
								<div class="wrapper">
									<div class="bg"><input class="input" type="password" name="cpassword" id="cpassword"></div>Retype Password:
								</div>
								<span class="error"><?php echo $cpassErr.$passMatchErr;?></span>
								<a href="#" class="button" onclick="document.getElementById('registration').submit()">Register</a>
								<a href="#" class="button" onclick="document.getElementById('registration').reset()">clear</a>
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
<script type="text/javascript"> Cufon.now(); </script>
</body>
</html>