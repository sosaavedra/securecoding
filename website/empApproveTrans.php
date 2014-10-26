<!DOCTYPE html>
<html lang="en">
<head>
<title>Approve transfers</title>
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
					<li class="alpha" id="menu_active"><a href="#"><span><span>Approve transfers</span></span></a></li>
					<li><a href="empApproveReg.php"><span><span>Approve client</span></span></a></li>
					<li><a href="empViewCustomer.php"><span><span>View client</span></span></a></li>
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
							<p>Please select transaction(s) you want to approve OR reject</p>
							<form id="approvalTrans" class="formstyle" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
											
						<?php
						
						require_once "includes/config.php";
						require_once "classes/mysqliconn.php";
						
						$mysqli = new MysqliConn ();
						$mysqli->connect ();
						
						// define variables for form values validation and set to empty values
						$chekBoxErr = "";
						$formValid = true;
						
						// checking if request is a posted
						if ($_POST) {
							
							if (empty ( $_POST ['check_list'] )) {
								$formValid = false;
								$chekBoxErr = "No transactions selected";
							} else {
								
								foreach ( $_POST ['check_list'] as $id ) {
									
									$clientId = $mysqli->escape ( $clientId );
									
									if (isset ( $_POST ['approve'] )) {
										
										// approve-button was clicked
										if ($mysqli->approveTransaction ( $id )) {
											$mysqli->close ();
										} else {
											die ( "Error: in approving!" );
										}
										// move the data to transaction history table
									} else if (isset ( $_POST ['reject'] )) {
										// reject-button was clicked
										// delete from transaction table and refund balance to client
										if ($mysqli->rejectTransaction ( $id )) {
											$mysqli->close ();
										} else {
											die ( "Error:  in rejecting!" );
										}
									}
								}
							}
						}
						
						$result = $mysqli->getTransactionsToApprove ();
						
						if (!empty($result) && $result->num_rows > 0) {
							
							echo "<div class='datagrid'><table>";
							echo "<thead><tr> <td> Origin </td> <td> Destination </td> <td> Date </td> <td> Amount </td><td> Type </td> <td> Approve/Reject </td> </tr></thead>";
							echo "<tbody>";
							
							// output data of each row
							while ( $row = $result->fetch_assoc () ) {
								echo "<tr class='alt'>";
								echo "<td>" . $row ["origin_account_id"] . "</td><td>" . $row ["destination_account_id"] . "</td><td>" . $row ["created_date"] . "</td><td>" . $row ["amount"] . "</td><td>" . $row ["transaction_type_id"] . "</td><td><input type='checkbox' name='check_list[]' value='" . $row ["id"] . "' checked></td>";
								echo "</tr>";
							}
							echo "</tbody>";
							echo "</table></div>";
							
							echo "<input class='button' type='submit' name='approve' value='Approve' id='approve'>";
							echo "<input class='button' type='submit' name='reject' value='Reject' id='reject'>";
						} else {
							echo "No transaction are waiting approval";
						}
						
						$mysqli->close ();
						?>
						</form>
							<span class="error"><?php echo $chekBoxErr;?></span>
						</article>
					</div>
				</div>
			</div>
		</section>
		<!-- / content -->
	</div>
</body>
</html>