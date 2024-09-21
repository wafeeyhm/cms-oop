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
        $object_array = array();

        while ($row = mysqli_fetch_array($result)) {
            # code...
            $object_array[] = self::instantiation($row);
        }

        return $object_array;
    }

    public static function instantiation($record){

        $object = new self;

        // $object->id  = $found_user['id'];
        // $object->username = $found_user['username'];
        // $object->password = $found_user['password'];
        // $object->first_name = $found_user['first_name'];
        // $object->last_name = $found_user['last_name'];

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