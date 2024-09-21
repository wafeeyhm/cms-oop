<?php

class User{

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

}

?>