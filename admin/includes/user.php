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

        $sql = "SELECT * FROM users WHERE username ='{$username}' AND password = '{$password}'   LIMIT 1";
        
        $result_array = self::run_query($sql);

        //using ternary
        return !empty($result_array) ? array_shift($result_array) : false;

    }

    //start CRUD

    public function save(){
        return isset($this->id) ? $this->update() : $this->create();
    }

    //create method
    public function create(){

        global $database;

        //insert sql
        $sql = "INSERT INTO users (username, password, first_name, last_name)";
        $sql .= "VALUES ('";
        $sql .= $database->escape_string($this->username) . "','";
        $sql .= $database->escape_string($this->password) . "','";
        $sql .= $database->escape_string($this->first_name) . "','";
        $sql .= $database->escape_string($this->last_name) . "')";

        if ($database->query($sql)) {
            # code...

            $this->id = $database->the_insert_id();

            return true;

        } else {
            # code...

            return false;

        }

    }

    //update method

    public function update(){

        global $database;

        $sql = "UPDATE users SET ";
        $sql .= "username= '" . $database->escape_string($this->username) ."',";
        $sql .= "password= '" . $database->escape_string($this->password) ."',";
        $sql .= "first_name= '" . $database->escape_string($this->first_name) ."',";
        $sql .= "last_name= '" . $database->escape_string($this->last_name) ."' ";
        $sql .= "WHERE id=" . $database->escape_string($this->id);

        $database->query($sql);

        return (mysqli_affected_rows($database->connection) == 1) ? true : false;
        
    }

    //delete method

    public function delete(){

        global $database;

        $sql = "DELETE FROM users ";
        $sql .= "WHERE id=" . $database->escape_string($this->id) . " LIMIT 1";

        $database->query($sql);

        return (mysqli_affected_rows($database->connection) == 1) ? true : false;

    }

    //end CRUD

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