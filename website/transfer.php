<!DOCTYPE html>
<html lang="en">
<head>
<title>Make a transaction</title>
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<!--[if lt IE 9]>
    <script type="text/javascript" src="http://info.template-help.com/files/ie6_warning/ie6_script_other.js"></script>
    <script type="text/javascript" src="js/html5.js"></script>
<![endif]-->
</head>
<body id="page4">
<?php

// define variables for form values validation and set to empty values
$transNoErr = $amountErr = $toAccountErr = $transTypeErr = "";
$formValid = true;

// connect to DB
$con = mysqli_connect ( "localhost", "root", "samurai", "banksys" );
// Check connection
if (mysqli_connect_errno ()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error ();
}

// checking if request is posted
if ($_POST) {
	
	if (empty ( $_POST ['transactionType'] )) {
		$transTypeErr = "Transaction type not valid";
		$formValid = false;
	}
	if (empty ( $_POST ['transNo'] )) {
		$transNoErr = "Transaction number is required";
		$formValid = false;
	}
	if (empty ( $_POST ['amount'] )) {
		$amountErr = "Amount is required";
		$formValid = false;
	}
	if (empty ( $_POST ['toAccount'] )) {
		$toAccountErr = "To account is required";
		$formValid = false;
	}
	
	if ($formValid) {
		
		// escape variables for security
		$transactionType = mysqli_real_escape_string ( $con, $_POST ['transactionType'] );
		$transNo = mysqli_real_escape_string ( $con, $_POST ['transNo'] );
		$amount = mysqli_real_escape_string ( $con, $_POST ['amount'] );
		$toAccount = mysqli_real_escape_string ( $con, $_POST ['toAccount'] );
		
		// header ( 'Location: transferSuccess.html' );
	}
}

mysqli_close ( $con );

?>

<div class="main">
		<!-- header -->
		<header>
			<div class="wrapper">
				<h1>
					<a href="index.html" id="logo">Smart Biz</a>
				</h1>
			</div>
			<nav>
				<ul id="menu">
					<li class="alpha"><a href="index.html"><span><span>Home</span></span></a></li>
					<li><a href="customerPage.php"><span><span>My Dashboard</span></span></a></li>
					<li id="menu_active"><a href="#"><span><span>Transfer Money</span></span></a></li>
					<li class="omega"><a href="#"><span><span>Logout</span></span></a></li>
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
							<h2>Make a transaction</h2>
						</article>
						<article class="col2 pad_left1">
							<h2>Contact us</h2>
						</article>
					</div>
				</div>
				<div class="box pad_bot1">
					<div class="pad marg_top">
						<article class="col1">
							<form id="transaction" class="formstyle" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
								<div>
									<div class="wrapper">
										<div>
											<select name="transactionType" id="transactionType" class="bg">
												<option value="T">Transfer Money</option>
												<option value="D">Deposit Money</option>
												<option value="W">Withdraw Money</option>
											</select>
										</div>
										Select type:
									</div>
									<span class="error"><?php echo $transTypeErr;?></span>
									<div class="wrapper">
										<div class="bg">
											<input class="input" type="text" name="amount" id="amount">
										</div>
										Amount:
									</div>
									<span class="error"><?php echo $amountErr;?></span>
									<div class="wrapper" id="toAccountDiv">
										<div class="bg">
											<input class="input" type="text" name="toAccount" id="toAccount">
										</div>
										To Account:
									</div>
									<span class="error"><?php echo $toAccountErr;?></span>
									<div class="wrapper" id="transactionNoDiv">
										<div class="bg">
											<input class="input" type="text" name="transNo" id="transNo">
										</div>
										Transaction no:
									</div>
									<span class="error"><?php echo $transNoErr;?></span>
									<div class="wrapper">
										<div style="margin-right: 100px">
										<input class='button' type='submit' name='transfer' value='Go' id='transfer'>
										</div>
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
</body>
</html>
