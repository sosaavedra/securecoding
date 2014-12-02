<?php

    require_once 'includes/checkSession.php';
    require_once 'includes/employeeAccessOnly.php';
    $empName = $_SESSION ['logged_user']-> first_name." ".$_SESSION ['logged_user']-> last_name;

?>

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
                <a href="index.html" id="logo">BankSys</a>
            </div>
            <nav>
                <ul id="menu">
                    <li class="alpha"><a href="empApproveTrans.php"><span><span>Approve Transfers</span></span></a></li>
                    <li><a href="empApproveReg.php"><span><span>Approve Client</span></span></a></li>
                    <li id="menu_active"><a href="empViewCustomer.php"><span><span>View Client</span></span></a></li>
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
                            <h2>Welcome <?php echo $empName; ?></h2>
                        </article>
                    </div>
                </div>
                <div class="box pad_bot1">
                    <div class="pad marg_top">
                        <article style="width:500px">
                            <p>Please enter account number you want to view</p>
                            <form id="viewDetails" class="formstyle" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

                                <div class="wrapper">
                                    <div class="wrapper">
                                        <div class="bg">
                                            <input class="input" type="text" name="accountNumber" id="accountNumber" value="<?php if(isset($_POST ['accountNumber']))echo $_POST ['accountNumber']; ?>">
                                        </div>
                                        Account Number:
                                    </div>
                                    <div style="margin-right: 100px">
                                        <input class='button' type='submit' name='viewCustomer' value='Search' id='viewCustomer'>
                                    </div>
                                </div>

                            </form>
                        </article>
                        <article>
                            <?php
                            
                            require_once "includes/utils.php";
                            require_once "includes/config.php";
                            require_once "classes/mysqliconn.php";
                            
                            // define variables for form values validation and set to empty values
                            $accountNumberErr = "";
                            $formValid = true;
                            
                            if ($_POST) {
                                
                                if (empty ( $_POST ['accountNumber'] )) {
                                    $accountNumberErr = "account number not valid";
                                    $formValid = false;
                                }
                                
                                if ($formValid) {
                                    
                                    $mysqli = new MysqliConn ();
                                    $mysqli->connect ();
                                    
                                    $accountNumber = $mysqli->escape ( $_POST ['accountNumber'] );
                                    
                                    // to show customer details
                                    $result = $mysqli->getAccountDetails ( $accountNumber );
                                    
                                    if (!empty($result) && $result->num_rows > 0) {
                            ?>                                        
                                        <h3> Customer details: </h3>
                                        <br/>
                                        <div class='datagrid'>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <td>Account Number</td>
                                                        <td>Name</td>
                                                        <td>Email</td>
                                                        <td>Balance</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class='alt'>
                                                    <?php
                                                        $row = $result->fetch_assoc ();
                                                        printf("<td>%s</td><td>%s</td><td>%s</td><td>%s</td>",
                                                            $row['account_number'],
                                                            $row['description'] . " " . $row ['first_name'] . " " . $row ['last_name'],
                                                            $row ['email'], moneyFormat($row ['balance']));
                                                    ?>                         
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                       <?php 
                                        // to show customer transactions
                                        $result = $mysqli->getAccountTransactionHistory ( $accountNumber );
                                        
                                        if (!empty($result) && $result->num_rows > 0) {
                                        ?>
                                        <br>
                                        <div class='datagrid'><table>
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
                                            echo "<br><h3>No transaction history found</h3>";
                                        }
                                    } else {
                                        echo "Invalid Account Number.";
                                    }
                                    
                                    $mysqli->close ();
                                }
                            }
                            
                            ?>
                            <span class="error"><?php echo $accountNumberErr;?></span>
                        </article>
                    </div>
                </div>
            </div>
        </section>
        <!-- / content -->
    </div>
</body>
</html>
