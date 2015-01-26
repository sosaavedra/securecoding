<?php

    require_once "includes/checkOrigin.php";
    require_once 'includes/checkSession.php';
    require_once 'includes/customerAccessOnly.php';
    
    $customerId = $_SESSION ['logged_user']-> id;
    $customerName = $_SESSION ['logged_user']-> first_name." ".$_SESSION ['logged_user']-> last_name;
    $accountNumber = $_SESSION ['logged_user']-> account_number;

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
                <a href="index.php" id="logo">BankSys</a>
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
                        require_once "includes/utils.php";
                        require_once "includes/config.php";
                        require_once "classes/mysqliconn.php";
                        
                        // Create connection
                        $mysqli = new MysqliConn ();
                        $mysqli->connect ();
                        
                        $result = $mysqli->getClientAccountAndBalance ( $customerId );
                        if (!empty($result) && $result->num_rows > 0) {
                                $row = $result->fetch_assoc ();
                                echo "<h3>Your account number: $accountNumber</h3>";
                                echo "<h3>Your balance: ". moneyFormat($row ["balance"])."</h3>";
                                if($row['use_scs'] === 'Y'){
                                	echo "<h3><a href='download.php?download_file=SCSTeam17.jar'>Download SCS</a> </h3>";
                                	$scsresult = $mysqli->getSCSPin ( $customerId );
                                	if (!empty($scsresult) && $scsresult->num_rows > 0) {
                                		$scsrow = $scsresult->fetch_assoc ();
                                		echo "<h3>Your PIN for SCS:".$scsrow["pin_code"]."</h3>";
                                	}
                                }
                        }
                        
                        $result = $mysqli->getAccountTransactionHistory ( $accountNumber );
                        ?>

                        <br/><br/>
                        <h3>Transaction history</h3>
                        <br/>

                        <?php
                        if (!empty($result) && $result->num_rows > 0) {
                        ?>
                         <div class='datagrid' style="width:800px">
                            <table>
                                <thead>
                                    <tr>
                                        <td>From Account</td>
                                        <td>Name</td>
                                        <td>To Account</td>
                                        <td>Name</td>
                                        <td>Amount</td>
                                        <td>Description</td>
                                        <td>Date</td>
                                    </tr>
                                </thead>
                                <tbody>
                        <?php 
                            // output data of each row
                            while ( $row = $result->fetch_assoc () ) {
                                echo "<tr class='alt'>";
                                printf("<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>",
                                    $row ["origin"],
                                    $row ["origin_name"],
                                    $row ["destination"],
                                    $row ["destination_name"],
                                    moneyFormat($row ["amount"]),
                                    $row ["description"],
                                    $row ["rejected_date"] ? $row ["rejected_date"] : ($row ["approved_date"] ? $row ["approved_date"] : $row ["created_date"]));
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
