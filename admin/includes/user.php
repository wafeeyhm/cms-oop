<?php

class User{

    public static function find_all_users(){
        global $database;

        $result = $database->query("SELECT * FROM users");
        return $result;
    }

    public static function find_users_by_id($user_id){
        global $database;

        $result = $database->query("SELECT * FROM users WHERE id=" . $user_id);
        $found_user = mysqli_fetch_array($result);
        return $found_user;
    }

}

?>