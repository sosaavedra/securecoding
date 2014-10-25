<?php

require_once 'inexistentPropertyException.php';
require_once 'classes/employee.php';

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
            echo "victor $propery";
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
        }
    }

    public function escape($str){
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

        if($isEmployee){
            $stmt->prepare("call employeeLogin (?, ?)");
        } else {
            $stmt->prepare("call clientLogin (?, ?)");
        }

        $stmt->bind_param('ss', $user, $password);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_object("Employee");
    }

    public function clientLogin($user, $password){
        return $this->login($user, $password);
    }

    public function employeeLogin($user, $password){
        $result =  $this->login($user, $password, true);
        //TODO instance employe object
    }
}

?>