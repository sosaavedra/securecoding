<?php

    require_once 'includes/checkSession.php';
    
    require_once 'includes/customerAccessOnly.php';
    
    $customerId = $_SESSION ['logged_user']-> id;
    $customerName = $_SESSION ['logged_user']-> first_name." ".$_SESSION ['logged_user']-> last_name;
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Dashboard</title>
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
					<li class="alpha" id="menu_active"><a href="customerPage.php"><span><span>My Dashboard</span></span></a></li>
					<li><a href="transfer.php"><span><span>Transfer Money</span></span></a></li>
					<li class="omega"><a href="logout.php"><span><span>Logout</span></span></a></li>
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
							<h2>Welcome <?php echo $customerName; ?></h2>
						</article>
						<article class="col2 pad_left1">
							<h2>Make a transaction</h2>
						</article>
					</div>
				</div>
				<div class="box pad_bot1">
					<div class="pad marg_top">
						<article class="col1">
						
						<?php
						require_once "includes/config.php";
						require_once "classes/mysqliconn.php";
						
						// Create connection
						$mysqli = new MysqliConn ();
						$mysqli->connect ();
						
						$result = $mysqli->getClientAccountAndBalance ( $customerId );
						if (!empty($result) && $result->num_rows > 0) {
								$row = $result->fetch_assoc ();
								echo "<h3>Your account number: ".$row ["account_number"]."</h3>";
								echo "<h3>Your balance: ".$row ["balance"]."</h3>";
						}
						
						$result = $mysqli->getClientTransactionHistory ( $customerId );
						
						echo "<br><br><h3>Transaction history</h3>";
						
						if (!empty($result) && $result->num_rows > 0) {
							
							echo "<div class='datagrid'><table>";
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
							echo "No transaction history found";
						}
						$mysqli->close ();
						
						?>
						
					</article>
						<article class="col2 pad_left1">
							<div class="wrapper">
								<p class="pad_bot3">Click below to make a new transaction
								
								
								<ul id="menu">
									<li id="menu_active"><a href="transfer.php"><span><span>Transfer Money</span></span></a></li>
								</ul>
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
