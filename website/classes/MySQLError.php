<?php

require_once "inexistentPropertyException.php";

class MySQLError{
    private $level;
    private $code;
    private $message;

    public function __get($property){
        if(property_exists($this, $property)){
            return $this->$property;
        } else {
            throw new InexistentPropertyException("Inexistent property: $property");
        }
    }

    public function getClassName(){
        return get_class($this);
    }

    public function __set($property, $value){
        if(property_exists($this, $property)){
            $this->$property = $value;
        } else {
            throw new InexistentPropertyException("Inexistent property: $property");
        }
    }
}

?>
