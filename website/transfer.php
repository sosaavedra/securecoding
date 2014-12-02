<?php
require_once 'includes/checkSession.php';

require_once 'includes/customerAccessOnly.php';
?>

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
$transNoErr = $amountErr = $toAccountErr = $transTypeErr = $uploadErr = "";
$formValid = true;

// checking if request is posted
if ($_POST) {
    
    require_once "includes/config.php";
    require_once "classes/mysqliconn.php";
    
    $client_id = $_SESSION ['logged_user']->id;
    
    if (isset ( $_POST ['transfer'] )) {
        // transfer-button was clicked
        if (empty ( $_POST ['transactionType'] )) {
            $transTypeErr = "Transaction type not valid";
            $formValid = false;
        }
        
        if (empty ( $_POST ['amount'] )) {
            $amountErr = "Amount is required";
            $formValid = false;
        }
        
        if (empty ( $_POST ['transNo'] )) {
            $transNoErr = "Transaction number is required";
            $formValid = false;
        }
        
        if (strcmp ( $_POST ['transactionType'], "3" ) == 0 && empty ( $_POST ['toAccount'] )) {
            $toAccountErr = "To account is required";
            $formValid = false;
        }
        
        if ($formValid) {
            
            // connect to DB
            $mysqli = new MysqliConn ();
            $mysqli->connect ();
            
            // escape variables for security
            $transactionType = $mysqli->escape ( $_POST ['transactionType'] );
            $transNo = $mysqli->escape ( $_POST ['transNo'] );
            $amount = $mysqli->escape ( $_POST ['amount'] );
            $toAccount = $mysqli->escape ( $_POST ['toAccount'] );
            
            $result = $mysqli->performTransaction ( $client_id, $toAccount, $amount, $transNo, $transactionType );

            if($result && $result->num_rows > 0){
                $row = $result->fetch_assoc();
                $transNoErr = $row ['Message'];
            } else {
                header ( 'Location: transferSuccess.html' );
            }
            
            $mysqli->close ();
            // echo "Transfer Success";
        }
    } else if (isset ( $_POST ['upload'] )) {
        // upload-button was clicked
        
        if (! empty ( $_FILES ['uploadFile'] ['name'] )) {
            
            $target_dir = "uploads/";
            $target_dir = $target_dir . basename ( $_FILES ["uploadFile"] ["name"] );
            $fileValid = true;
            
            // Check if file already exists
            if (file_exists ( $target_dir . $_FILES ["uploadFile"] ["name"] )) {
                $uploadErr = "Sorry, file already exists.";
            }
            
            // Check file size 500 KB
            if (isset ( $uploadFile_size ) && $uploadFile_size > 500000) {
                $uploadErr = "Sorry, your file is too large.";
                $fileValid = false;
            }
            
            // Only txt files allowed
            if (isset ( $uploadFile_type ) && ! ($uploadFile_type == "text/plain")) {
                $uploadErr = "Sorry, only txt files are allowed.";
                $fileValid = false;
            }
            
            // Check if $uploadOk is set to 0 by an error
            if ($fileValid) {
                if (move_uploaded_file ( $_FILES ["uploadFile"] ["tmp_name"], $target_dir )) {
                    $uploadErr = shell_exec("./c-parser/parser $target_dir $client_id 2>&1");

                    if($uploadErr == ""){
                        header ( 'Location: transferSuccess.html' );
                    }
                } else {
                    $uploadErr = "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            $uploadErr = "Please select the file";
        }
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
                    <li class="alpha"><a href="customerPage.php"><span><span>My Dashboard</span></span></a></li>
                    <li id="menu_active"><a href="transfer.php"><span><span>Transfer Money</span></span></a></li>
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
                            <h2>Single Transaction</h2>
                        </article>
                        <article class="col2 pad_left1">
                            <h2>Batch Transactions</h2>
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
                                                <option value="3">Transfer</option>
                                                <option value="1">Deposit</option>
                                                <option value="2">Withdraw</option>
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
                        <article class="col2" style="width:330px">
                            <form class="formstyle" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
                                    <div class="wrapper">
                                            <label for="uploadFile">
                                                Please choose a file:
                                            </label>
                                            <input type="file" name="uploadFile" id="uploadFile" style="float:none" class="bg">
                                    </div>
                                    <div style="font-style:italic;font-weight:normal; height:73px">
                                    <span>
                                        Format file:
                                        <br/>
                                        &lt;Destination Account&gt;@&lt;Amount&gt;@&lt;TAN Code&gt;
                                    </span>
                                    </div>
                                    <span class="error"><?php echo $uploadErr;?></span>

                                    <div class="wrapper">
                                        <input class='button' type='submit' name='upload' value='Go' id='upload'>
                                    </div>
                            </form>
                        </article>
                    </div>
                </div>
            </div>
        </section>
        <!-- / content -->
    </div>
</body>
</html>
