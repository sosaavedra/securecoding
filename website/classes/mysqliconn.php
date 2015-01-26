<?php
require_once 'inexistentPropertyException.php';
require_once 'classes/employee.php';
require_once 'classes/client.php';
require_once 'classes/MySQLError.php';

class MysqliConn {
    private $host;
    private $username;
    private $pwd;
    private $db;
    private $conn;
    private $isConnected;

    public function __construct($host = BANKSYS_HOST, $username = BANKSYS_USER, $pwd = BANKSYS_PWD, $db = BANKSYS_DB) {
        $this->host = $host;
        $this->username = $username;
        $this->pwd = $pwd;
        $this->db = $db;
    }

    public function __destruct() {
        $this->close ();
    }

    public function __get($property) {
        if (property_exists ( $this, $property )) {
            return $this->$property;
        } else {
            throw new InexistentPropertyException ( "Inexistent property: $property" );
        }
    }

    public function __set($property, $value) {
        if (property_exists ( $this, $property )) {
            $this->$property = $value;
        } else {
            throw new InexistentPropertyException ( "Inexistent property: $property" );
        }
    }

    public function connect() {
        $this->conn = new mysqli ( $this->host, $this->username, $this->pwd, $this->db ) or die ( 'Cannot connect to the database' );
        
        $this->conn->set_charset ( 'utf8' );
        $this->isConnected = true;
    }

    public function close() {
        if ($this->isConnected) {
            $this->conn->close ();
            $this->isConnected = false;
        }
    }

    public function escape($str) {
        $this->checkConnection ();
        
        return $this->conn->escape_string ( $str );
    }

    private function checkConnection() {
        if (! $this->isConnected) {
            $this->connect ();
        }
    }

    private function login($user, $password, $isEmployee = false) {
        $this->checkConnection ();
        
        $stmt = $this->conn->stmt_init ();
        $objectType = "";
        
        if ($isEmployee) {
            $stmt->prepare ( "call employeeLogin (?, ?)" );
            $objectType = "Employee";
        } else {
            $stmt->prepare ( "call clientLogin (?, ?)" );
            $objectType = "Client";
        }
        
        $stmt->bind_param ( 'ss', $user, $password );
        $stmt->execute ();
        
        $result = $stmt->get_result ();
        $cols = $result->field_count;
        
        if($cols == 3) {
            $objectType = "MySQLError";
        }

        return $result->fetch_object ( $objectType );
    }

    public function clientLogin($user, $password) {
        return $this->login ( $user, $password );
    }

    public function employeeLogin($user, $password) {
        return $this->login ( $user, $password, true );
    }

    public function createClient($titleId, $firstName, $lastName, $e_mail, $password, $tanOption) {
    	$title_type_id = $this->escape ( $titleId );
    	$first_name = $this->escape ( $firstName );
    	$last_name = $this->escape ( $lastName );
    	$email = $this->escape ( $e_mail );

    	$scsOpt = 'N';
    	if($tanOption === "scs"){
    		$scsOpt = 'Y';
    	}
    	
    	$pwd = hash ( 'sha256', $password );
    	
        $stmt = $this->conn->stmt_init ();
        $stmt->prepare ( "call createClient (?, ?, ?, ?, ?, ?)" );
        $stmt->bind_param ( 'isssss', $title_type_id, $first_name, $last_name, $email, $pwd, $scsOpt);
        $success = $stmt->execute ();
        return $success;
    }
    
    /**
     * Return the titles configured in database
     *
     * @return mysqli_result
     */
    public function getTitleTypes() {
        $stmt = $this->conn->stmt_init ();
        $stmt->prepare ( "call getTitleTypes" );
        $stmt->execute ();
        
        return $stmt->get_result ();
    }
    
    /**
     * this method processes transations.
     *
     * If amount < 10000, directly insert into transation_history table and update balances.
     * If amount > 10000, insert into transation table, balance reduced from client but deposited to destination
     * only after approval.
     *
     * @param
     *            customer initiating tranfer $client_id
     * @param
     *            Withdrawl, Deposit or Transfer $transactionType
     * @param
     *            destination account $toAccount
     * @param amount $amount            
     * @param
     *            unique transaction number $transNo
     * @return boolean
     */
    public function performTransaction( $client_id, $toAccount, $amount,$transNo, $transactionType  ) {
        $stmt = $this->conn->stmt_init ();
        $stmt->prepare ( "call performTransaction(?, ?, ?, ?, ?)" );
        $stmt->bind_param ( 'isdsi', $client_id, $toAccount, $amount, $transNo, $transactionType );
        $stmt->execute ();

        return $stmt->get_result ();
    }
    
    /**
     * Returns all the clients from client table who are not yet approved
     *
     * @return mysqli_result
     */
    public function getClientsToApprove() {
        $stmt = $this->conn->stmt_init ();
        $stmt->prepare ( "call getClientsToApprove" );
        $stmt->execute ();
        
        return $stmt->get_result ();
        ;
    }
    
    /**
     * return the transactions having more than 10000 euros values, for approval
     *
     * @return mysqli_result
     */
    public function getTransactionsToApprove() {
        $stmt = $this->conn->stmt_init ();
        $stmt->prepare ( "call getTransactionsToApprove" );
        $stmt->execute ();
        
        return $stmt->get_result ();
    }
    
    /**
     * returns the successful transactions of a particular client
     *
     * @param
     *            customer id $client_id
     * @return mysqli_result
     */
    public function getAccountTransactionHistory($accountNumber) {
        $stmt = $this->conn->stmt_init ();
        $stmt->prepare ( "call getAccountTransactionHistory (?)" );
        $stmt->bind_param ( 's', $accountNumber );
        $stmt->execute ();
        
        return $stmt->get_result ();
    }
    
    /**
     * returns the details of a particular client
     *
     * @param
     *            customer id $client_id
     * @return mysqli_result
     */
    public function getAccountDetails($accountNumber) {
        $stmt = $this->conn->stmt_init ();
        $stmt->prepare ( "call getAccountDetails (?)" );
        $stmt->bind_param ( 's', $accountNumber );
        $stmt->execute ();
        
        return $stmt->get_result ();
    }
    
    /**
     * returns the transation codes of a particular client
     *
     * @param
     *            customer id $client_id
     * @return mysqli_result
     */
    public function getClientTransationNumbers($client_id) {
        $stmt = $this->conn->stmt_init ();
        $stmt->prepare ( "call getClientTransationNumbers (?)" );
        $stmt->bind_param ( 'i', $client_id );
        $stmt->execute ();
    
        return $stmt->get_result ();
    }
    
    /**
     * creates the account for the client after approval so that he can login, also gives him some initial balance
     *
     * @param
     *            employee id $employee_id
     * @param
     *            client id $client_id
     * @return boolean
     */
    public function createAccount($employee_id, $client_id) {
        $stmt = $this->conn->stmt_init ();
        $stmt->prepare ( "call createAccount (?, ?)" );
        $stmt->bind_param ( 'ii', $employee_id, $client_id );
        $stmt->execute ();
        
        $result = $stmt->get_result ();
        
        return $result;
    }
    
    /**
     * delete the client from the table
     *
     * @param
     *            $client_id
     * @return boolean
     */
    public function deleteRejectedClient($client_id) {
        $stmt = $this->conn->stmt_init ();
        $stmt->prepare ( "call deleteRejectedClient (?)" );
        $stmt->bind_param ( 'i', $client_id );
        $stmt->execute ();
        
        return $stmt->get_result ();
    }
    
    /**
     * Once approved delete from transaction table and move to transaction_history table.
     * update the destination account balance.
     *
     * @param transactionId $id            
     * @return boolean
     */
    public function approveTransaction($id, $employee_id) {
        $stmt = $this->conn->stmt_init ();
        $stmt->prepare ( "call approveTransaction (?, ?)" );
        $stmt->bind_param ( 'ii', $id, $employee_id );
        $stmt->execute ();
        
        return $stmt->get_result ();
    }
    
    /**
     * Delete from transaction table and refund balance to client
     *
     * @param transactionId $id            
     * @return boolean
     */
    public function rejectTransaction($id, $employee_id) {
        $stmt = $this->conn->stmt_init ();
        $stmt->prepare ( "call rejectTransaction (?, ?)" );
        $stmt->bind_param ( 'ii', $id, $employee_id );
        $stmt->execute ();
        
        return $stmt->get_result ();
    }

        /**
     * returns the balance and account number of a particular client
     *
     * @param
     *            customer id $client_id
     * @return mysqli_result
     */
    public function getClientAccountAndBalance($client_id) {
        $stmt = $this->conn->stmt_init ();
        $stmt->prepare ( "call getClientAccountAndBalance (?)" );
        $stmt->bind_param ( 'i', $client_id );
        $stmt->execute ();
    
        return $stmt->get_result ();
    }

    public function generateClientTransactionCodes($client_id) {
        $stmt = $this->conn->stmt_init ();
        $stmt->prepare ( "call generateClientTransactionCodes(?)" );
        $stmt->bind_param ( 'i', $client_id);
        $stmt->execute ();

        return $stmt->get_result ();
    }
    
    public function forgetPassword($email) {
    	$stmt = $this->conn->stmt_init ();
    	$stmt->prepare ( "call forgetPassword(?)" );
    	$stmt->bind_param ( 's', $email);
    	$stmt->execute ();
    
    	return $stmt->get_result ();
    }  
    
    public function resetPassword($email, $token, $hashedPW) {
    	$stmt = $this->conn->stmt_init ();
    	$stmt->prepare ( "call resetPassword(?,?,?)" );
    	$stmt->bind_param ( 'sss', $email, $token, $hashedPW);
    	$stmt->execute ();
    
    	return $stmt->get_result ();
    }
    
    public function getClientPaswordToken($email) {
    	$stmt = $this->conn->stmt_init ();
    	$stmt->prepare ( "call getClientPaswordToken(?)" );
    	$stmt->bind_param ( 's', $email);
    	$stmt->execute ();
    
    	return $stmt->get_result ();
    }
    
    public function getSCSPin($client_id) {
    	$stmt = $this->conn->stmt_init ();
    	$stmt->prepare ( "call getSCSPin(?)" );
    	$stmt->bind_param ( 's', $client_id);
    	$stmt->execute ();
    	return $stmt->get_result ();
    }
}

?>
