<?php

/**
 * Error reporting
 */
//error_reporting(E_ALL);
error_reporting(0);
ini_set("display_errors", 1);


/**
 * URL
 */
define('URL', 'https://'. $_SERVER['SERVER_ADDR'] . '/');

define('ROOT', '/var/www/bank/');
/**
 * Database Configuration
 */

define('DB_TYPE', 'mysql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'bermudabank');
define('DB_USER', 'dbserver');
define('DB_PASS', 'localmachine');


/**
 * Database Constants' declarations
 */


// account table
define('ACCOUNT', 'account');
define('ACCOUNT_ACCOUNTNUMBER','accountNumber');
define('ACCOUNT_ACCOUNTHOLDER','accountHolder');
define('ACCOUNT_CREATEDDATE','createdDate');
define('ACCOUNT_MODIFIEDDATE','modifiedDate');
define('ACCOUNT_BALANCE','balance');
define('ACCOUNT_ACTIVE','active');
define('ACCOUNT_DOES_NOT_EXIST', 'Account does not exist. ');
define('ACCOUNT_WITHDRAW_DONE', 'Account withdraw done. ');
define('ACCOUNT_DEPOSIT_DONE', 'Account deposit done. ');
define('ACCOUNT_TRANSFER_DONE', 'Account transfer done. ');
define('ACCOUNT_TRANSFER_FAIL', 'Account transfer failed');
define('ACCOUNT_FOREIGN_TARGET', 'Target is foreign. ');
define('ACCOUNT_INSUFFICIENT_BALANCE', 'Not enough balance. ');

//passwordRecoveryToken
define('TOKEN','token');
define('TOKEN_USERID','userId');
define('TOKEN_CREATEDDATE','createdDate');
define('TOKEN_TOKEN','token');
define('TOKEN_ISPASSWORD','isPassword');

// bankuser table
define('BANKUSER', 'bankuser');
define('BANKUSER_FIRSTNAME','firstName');
define('BANKUSER_LASTNAME','lastName');
define('BANKUSER_CREATEDDATE','createdDate');
define('BANKUSER_MODIFIEDDATE','modifiedDate');
define('BANKUSER_BANKUSERID','bankUserId');
define('BANKUSER_EMAIL','email');
define('BANKUSER_MOBILE','mobile');
define('BANKUSER_ACTIVE','active');
define('BANKUSER_PASSWORD','passwd');
define('BANKUSER_PRIVILEGE','privilege');
define('BANKUSER_USESSECUREPIN','useSecurePin');
define('BANKUSER_VERIFIED','verified');


// datatransactionlog table
define('DATATRANSACTIONLOG', 'datatransactionlog');
define('DATATRANSACTIONLOG_LOGID','logId');
define('DATATRANSACTIONLOG_CREATEDDATE','createDate');
define('DATATRANSACTIONLOG_USERID','userId');
define('DATATRANSACTIONLOG_ORIGINACCOUNTNUMBER','originAccountNumber');
define('DATATRANSACTIONLOG_TARGETACCOUNTNUMBER','targetAccountNumber');
define('DATATRANSACTIONLOG_TRANSFERAMOUNT','transferAmount');
define('DATATRANSACTIONLOG_STATUS', 'status');
define('DATATRANSACTIONLOG_TYPE', 'type');
define('DATATRANSACTIONLOG_DESCRIPTION', 'description');


// tan table
define('TAN', 'tan');
define('TAN_USERID','userId');
define('TAN_TAN','tan');
define('TAN_TANSEQUENCEID','tanSequenceId');
define('TAN_ACTIVE','active');


// securepin table
define('SECUREPIN','securepin');
define('SECUREPIN_USERID', 'UserID');
define('SECUREPIN_PIN', 'SecurePin');

//messeges
define('BANK_TRANSACTION_DENIED', 'The bank transaction has been denied. ');
define('BANK_TRANSACTION_COMPLETE', 'The transaction has been processed. ');
define('BANK_TRANSACTION_FAIL', 'Requested transaction failed. ');
define('BANK_TRANSACTION_ALREADY_PROCESSED', 'The action cannot be completed. Transaction request already processed');

define('FOLDER_DELIMETER', '\/');
