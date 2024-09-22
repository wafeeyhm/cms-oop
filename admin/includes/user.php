<?php

class User{

    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;

    public static function find_all_users(){
        
        return self::run_query("SELECT * FROM users");

    }

    public static function find_users_by_id($user_id){
        
        $result_array =  self::run_query("SELECT * FROM users WHERE id=$user_id LIMIT 1");

        //using ternary
        return !empty($result_array) ? array_shift($result_array) : false;

    }

    public static function verify_user($username, $password){

        global $database;

        $username = $database->escape_string($username);
        $password = $database->escape_string($password);
        
        $result_array =  self::run_query("SELECT * FROM users WHERE username=$username AND password=$password LIMIT 1");

        //using ternary
        return !empty($result_array) ? array_shift($result_array) : false;

    }

    public static function run_query($sql){
        
        global $database;
        $result = $database->query($sql);
        $object_array = array();

        while ($row = mysqli_fetch_array($result)) {
            # code...
            $object_array[] = self::instantiation($row);
        }

        return $object_array;
    }

    public static function instantiation($record){

        $object = new self;

        foreach ($record as $attribute => $value) {
            //check if the object has the property using has_the_attribute()
            if($object->has_the_attribute($attribute)) {
                //dynamically assign the value to the correct property
                $object->$attribute = $value;
            }
        }

        return $object;

    }

    private function has_the_attribute($attribute){
        
        $object_properties = get_object_vars($this);

        return array_key_exists($attribute, $object_properties);
    }

}

?>