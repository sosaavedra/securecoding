<?php

require_once "inexistentPropertyException.php";

abstract class Person{
    private $id;
    private $title_type_id;
    private $title_type;
    private $first_name;
    private $last_name;
    private $email;
    private $user_type_id;
    private $user_type;
    private $last_login;

    function __get($property){
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
}

?>
