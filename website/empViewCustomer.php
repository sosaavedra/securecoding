<!DOCTYPE html>
<html lang="en">
<head>
<title>View client</title>
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
					<li class="alpha"><a href="empApproveTrans.php"><span><span>Approve transfers</span></span></a></li>
					<li><a href="empApproveReg.php"><span><span>Approve client</span></span></a></li>
					<li id="menu_active"><a href="#"><span><span>View client</span></span></a></li>
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
							<h2>Welcome</h2>
						</article>
					</div>
				</div>
				<div class="box pad_bot1">
					<div class="pad marg_top">
						<article class="col1">
							<p>Please enter customer id you want to view</p>
							<form id="viewDetails" class="formstyle" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

								<div class="wrapper">
									<div class="wrapper">
										<div class="bg">
											<input class="input" type="text" name="customerId" id="customerId" value="<?php if(isset($_POST ['customerId']))echo $_POST ['customerId']; ?>">
										</div>
										Customer Id:
									</div>
									<div style="margin-right: 100px">
										<input class='button' type='submit' name='viewCustomer' value='Go' id='viewCustomer'>
									</div>
								</div>

							</form>
							<?php
							
							require_once "includes/config.php";
							require_once "classes/mysqliconn.php";
							
							// define variables for form values validation and set to empty values
							$customerIdErr = "";
							$formValid = true;
							
							if ($_POST) {
								
								if (empty ( $_POST ['customerId'] )) {
									$customerIdErr = "customer id not valid";
									$formValid = false;
								}
								
								if ($formValid) {
									
									$mysqli = new MysqliConn ();
									$mysqli->connect ();
									
									$customerId = $mysqli->escape ( $customerId );
									
									// to show customer details
									
									$result = $mysqli->getClientDetails ( $customerId );
									
									if ($result->num_rows > 0) {
										
										echo "<h3> Customer details: </h3><br>";
										echo "<div class='datagrid'><table>";
										echo "<thead><tr> <td> Name </td> <td> Email </td> <td> ID </td></tr></thead>";
										echo "<tbody>";
										
										// output data of each row
										while ( $row = $result->fetch_assoc () ) {
											echo "<tr class='alt'>";
											echo "<td>" . $row ["first_name"] . " " . $row ["last_name"] . "</td><td>" . $row ["email"] . "</td><td>" . $row ["id"] . "</td>";
											echo "</tr>";
										}
										echo "</tbody>";
										echo "</table></div>";
										
										// to show customer transactions
										$result = $mysqli->getClientTransactionHistory ( $customerId );
										
										if ($result->num_rows > 0) {
											
											echo "<br><div class='datagrid'><table>";
											echo "<thead><tr> <td> To Account </td> <td> Date </td> <td> Amount </td><td> Type </td> </tr></thead>";
											echo "<tbody>";
											
											// output data of each row
											while ( $row = $result->fetch_assoc () ) {
												echo "<tr class='alt'>";
												echo "<td>" . $row ["destination_account_id"] . "</td><td>" . $row ["approved_date"] . "</td><td>" . $row ["amount"] . "</td><td>" . $row ["transaction_type_id"] . "</td>";
												echo "</tr>";
											}
											echo "</tbody>";
											echo "</table></div>";
										} else {
											echo "<br><h3>No transaction history found</h3>";
										}
									} else {
										echo "Invalid customer Id.";
									}
									
									$mysqli->close ();
								}
							}
							
							?>
							<span class="error"><?php echo $customerIdErr;?></span>
						</article>
					</div>
				</div>
			</div>
		</section>
		<!-- / content -->
	</div>
</body>
</html>