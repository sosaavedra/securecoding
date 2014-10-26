<!DOCTYPE html>
<html lang="en">
<head>
<title>Approve registrations</title>
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
					<li id="menu_active"><a href="#"><span><span>Approve client</span></span></a></li>
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
							<p>Please select client(s) you want to approve OR reject</p>
							<form id="approval" class="formstyle" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
											
						<?php
						
						// Create connection
						$conn = mysqli_connect ( "localhost", "root", "samurai", "banksys" );
						// Check connection
						if ($conn->connect_error) {
							die ( "Connection failed: " . $conn->connect_error );
						}
						
						// define variables for form values validation and set to empty values
						$chekBoxErr = "";
						$formValid = true;
						
						// checking if request is a posted
						if ($_POST) {
							
							if (empty ( $_POST ['check_list'] )) {
								$formValid = false;
								$chekBoxErr = "No clients selected";
							} else {
								
								foreach ( $_POST ['check_list'] as $clientId ) {
									
									$clientId = mysqli_real_escape_string ( $conn, $clientId );
									
									if (isset ( $_POST ['approve'] )) {
										// approve-button was clicked
										$date = date ( "Y-m-d H:i:s" );
										$sql = "UPDATE client SET activation_date=now(),activated_by=1 WHERE id='$clientId'";
										if (! mysqli_query ( $conn, $sql )) {
											die ( 'Error: ' . mysqli_error ( $conn ) );
										}
									} else if (isset ( $_POST ['reject'] )) {
										// reject-button was clicked
										$sql = "DELETE FROM client WHERE id='$clientId'";
										if (! mysqli_query ( $conn, $sql )) {
											die ( 'Error: ' . mysqli_error ( $conn ) );
										}
									}
								}
							}
						}
						
						$sql = "SELECT id,first_name,last_name,email FROM `client` WHERE activated_by='0' LIMIT 10";
						$result = $conn->query ( $sql );
						
						if ($result->num_rows > 0) {
							
							echo "<div class='datagrid'><table>";
							
							echo "<thead><tr> <td> Name </td> <td> Email </td> <td> ID </td><td> Approve/Reject </td> </tr></thead>";
							
							echo "<tbody>";
							
							// output data of each row
							while ( $row = $result->fetch_assoc () ) {
								echo "<tr class='alt'>";
								echo "<td>" . $row ["first_name"] . " " . $row ["last_name"] . "</td><td>" . $row ["email"] . "</td><td>" . $row ["id"] . "</td><td><input type='checkbox' name='check_list[]' value='" . $row ["id"] . "' checked></td>";
								echo "</tr>";
							}
							echo "</tbody>";
							echo "</table></div>";
							
							echo "<input class='button' type='submit' name='approve' value='Approve' id='approve'>";
							echo "<input class='button' type='submit' name='reject' value='Reject' id='reject'>";
						} else {
							echo "No clients pending approval.";
						}
						$conn->close ();
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