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
        
        $result =  self::run_query("SELECT * FROM users WHERE id=$user_id LIMIT 1");
        $found_user = mysqli_fetch_array($result);
        return $found_user;

    }

    public static function run_query($sql){
        
        global $database;
        $result = $database->query($sql);
        return $result;
    }

    public static function instantiation($found_user){

        $object = new self;

        $object->id  = $found_user['id'];
        $object->username = $found_user['username'];
        $object->password = $found_user['password'];
        $object->first_name = $found_user['first_name'];
        $object->last_name = $found_user['last_name'];

        return $object;

    }

}

?>