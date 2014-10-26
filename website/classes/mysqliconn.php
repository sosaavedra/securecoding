<?php

require_once 'inexistentPropertyException.php';
require_once 'classes/employee.php';
require_once 'classes/client.php';

class MysqliConn{
    private $host;
    private $username;
    private $pwd;
    private $db;

    private $conn;
    private $isConnected;

    public function __construct($host = BANKSYS_HOST, $username = BANKSYS_USER, $pwd = BANKSYS_PWD, $db = BANKSYS_DB){
        $this->host = $host;
        $this->username = $username;
        $this->pwd = $pwd;
        $this->db = $db;
    }

    public function __destruct(){
        $this->close();
    }

    public function __get($property){
        if(property_exists($this, $property)){
            return $this->$property;
        } else {
            throw new InexistentPropertyException("Inexistent property: $property");
        }
    }

    public function __set($property, $value){
        if(property_exists($this, $property)){
            $this->$property = $value;
        } else {
            throw new InexistentPropertyException("Inexistent property: $property");
        }

    }

    public function connect(){
        $this->conn = new mysqli($this->host, $this->username, $this->pwd, $this->db)
            or die('Cannot connect to the database'); 

        $this->conn->set_charset('utf8');
        $this->isConnected = true;
    }

    public function close(){
        if($this->isConnected){
            $this->conn->close();
            $this->isConnected = false;
        }
    }

    public function escape($str){
        $this->checkConnection();

        return $this->conn->escape_string($str);
    }

    private function checkConnection(){
        if(!$this->isConnected){
            $this->connect();
        }
    }

    private function login($user, $password, $isEmployee = false){
        $this->checkConnection();

        $stmt = $this->conn->stmt_init();
        $objectType = "";

        if($isEmployee){
            $stmt->prepare("call employeeLogin (?, ?)");
            $objectType = "Employee";
        } else {
            $stmt->prepare("call clientLogin (?, ?)");
            $objectType = "Client";
        }

        $stmt->bind_param('ss', $user, $password);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_object($objectType);
    }

    public function clientLogin($user, $password){
        return $this->login($user, $password);
    }

    public function employeeLogin($user, $password){
        return $this->login($user, $password, true);
    }

    public function createClient($title_type_id, $first_name, $last_name, $email, $pwd){
        $stmt = $this->conn->stmt_init();
        $stmt->prepare("call createClient (?, ?, ?, ?, ?)");
        $stmt->bind_param('issss', $title_type_id, $first_name, $last_name, $email, $pwd);
        $stmt->execute();

        $error_msg;
        $stmt->bind_result($error_msg);

        return !$stmt->fetch();
    }

    /**
     * Return the titles configured in database
     * @return mysqli_result
     */
    public function getTitleTypes(){
        $stmt = $this->conn->stmt_init();
        $stmt->prepare("call getTitleTypes");
        $stmt->execute();

        $result = $stmt->get_result();

        return $result;
    }
    
    /**
     * this method processes transations.
     * 
     * If amount < 10000, directly insert into transation_history table and update balances.
     * If amount > 10000, insert into transation table, balance reduced from client but deposited to destination
     *  only after approval.
     * 
     * @param customer initiating tranfer $client_id
     * @param Withdrawl, Deposit or Transfer $transactionType
     * @param destination account $toAccount
     * @param amount $amount
     * @param unique transaction number $transNo
     * @return boolean
     */
    public function doTransaction($client_id, $transactionType, $toAccount, $amount, $transNo){
    	$stmt = $this->conn->stmt_init();
    	$stmt->prepare("call createClient (?, ?, ?, ?, ?)");
    	$stmt->bind_param('isiis', $title_type_id, $first_name, $last_name, $email, $pwd);
    	$stmt->execute();
    
    	$error_msg;
    	$stmt->bind_result($error_msg);
    
    	return !$stmt->fetch();
    }
    
    /**
     * Returns all the clients from client table who are not yet approved
     * @return mysqli_result
     */
    public function getClientsToApprove(){
    	$stmt = $this->conn->stmt_init();
    	$stmt->prepare("call getClientsToApprove");
    	$stmt->execute();
    	$result = $stmt->get_result();
    	return $result;
    }
    
    /** 
     * return the transactions having more than 10000 euros values, for approval
     * @return mysqli_result
     */
    public function getTransactionsToApprove(){
    	$stmt = $this->conn->stmt_init();
    	$stmt->prepare("call getTransactionsToApprove");
    	$stmt->execute();
    	$result = $stmt->get_result();
    	return $result;
    }
    
    
    /**
     * returns the successful transactions of a particular client
     * @param customer id $client_id
     * @return mysqli_result
     */
    public function getClientTransactionHistory($client_id){
    	$stmt = $this->conn->stmt_init();
    	$stmt->prepare("call getClientTransactionHistory");
    	$stmt->bind_param('i',$client_id);
    	$stmt->execute();
    	
    	$error_msg;
    	$result = $stmt->get_result();
    	return $result;
    }
    
    /**
     * returns the details of a particular client
     * @param customer id $client_id
     * @return mysqli_result
     */
    public function getClientDetails($client_id){
    	$stmt = $this->conn->stmt_init();
    	$stmt->prepare("call getClientDetails");
    	$stmt->bind_param('i',$client_id);
    	$stmt->execute();
    	 
    	$error_msg;
    	$result = $stmt->get_result();
    	return $result;
    }
    
    
    /** 
     * creates the account for the client after approval so that he can login, also gives him some initial balance
     * @param employee id $employee_id
     * @param client id $client_id
     * @return boolean
     */
    public function createAccount($employee_id, $client_id){
    	$stmt = $this->conn->stmt_init();
    	$stmt->prepare("call createAccount (?, ?)");
    	$stmt->bind_param('ii', $employee_id, $client_id);
    	$stmt->execute();
    
    	$error_msg;
    	$stmt->bind_result($error_msg);
    
    	return !$stmt->fetch();
    }
    
    /** 
     * delete the client from the table
     * @param $client_id
     * @return boolean
     */
    public function deleteRejectedClient($client_id){
    	$stmt = $this->conn->stmt_init();
    	$stmt->prepare("call deleteRejectedClient (?)");
    	$stmt->bind_param('i',$client_id);
    	$stmt->execute();
    
    	$error_msg;
    	$stmt->bind_result($error_msg);
    
    	return !$stmt->fetch();
    }
    
    /** 
     * Once approved delete from transaction table and move to transaction_history table.
     * update the destination account balance.
     * @param transactionId $id
     * @return boolean
     */
    public function approveTransaction($id){
    	$stmt = $this->conn->stmt_init();
    	$stmt->prepare("call approveTransaction (?)");
    	$stmt->bind_param('i', $id);
    	$stmt->execute();
    
    	$error_msg;
    	$stmt->bind_result($error_msg);
    
    	return !$stmt->fetch();
    }
    
    /** 
     * Delete from transaction table and refund balance to client
     * @param transactionId $id
     * @return boolean
     */
    public function rejectTransaction($id){
    	$stmt = $this->conn->stmt_init();
    	$stmt->prepare("call rejectTransaction (?)");
    	$stmt->bind_param('i', $id);
    	$stmt->execute();
    
    	$error_msg;
    	$stmt->bind_result($error_msg);
    
    	return !$stmt->fetch();
    }


}

?>
