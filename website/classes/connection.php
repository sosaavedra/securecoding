<?php

class MysqliConn{
    private $host;
    private $username;
    private $pwd;
    private $db;

    private $conn;
    private $isConnected;

    public function __construct($host = NULL, $username = NULL, $pwd = NULL, $db = NULL){
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
        }
    }

    public function query($query){
        if(!$this->isConnected){
            $this->connect();
        }


    }

    private function login($user, $password, $isClient = false){
        if(!$isClient){
            $query = "employeeLogin '$user', '$password";
        } else {
            $query = "clientLogin '$user', '$password";
        }

        $result = $this->query($query);

       return ($result == null)? null : $result;
    }

    public function clientLogin($user, $password){
        $result = $this->login($user, $password, true);
        // TODO instance client object
    }

    public function employeeLogin($user, $password){
        $result =  $this->login($user, $password);
        //TODO instance employe object
    }
}

?>
