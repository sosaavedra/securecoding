<?php

require_once "inexistentPropertyException.php";

abstract class Person{
    protected $id;
    protected $title_type_id;
    protected $title_type;
    protected $first_name;
    protected $last_name;
    protected $email;
    protected $user_type_id;
    protected $user_type;
    protected $last_login;

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
