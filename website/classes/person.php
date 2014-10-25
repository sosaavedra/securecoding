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

    function __construct($id = null, $title_type_id = null, $title_type = null, $first_name = null, $last_name = null, $email = null, $last_login = null){
        $this->id = $id;
        $this->title_type_id = $title_type_id;
        $this->title_type = $title_type;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->last_login = $last_login;
    }

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
