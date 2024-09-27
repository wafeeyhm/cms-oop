<?php

class User extends Db_object{

    

    public static function verify_user($username, $password){

        global $database;

        $username = $database->escape_string($username);
        $password = $database->escape_string($password);

        $sql = "SELECT * FROM " .self::$db_table ." WHERE username ='{$username}' AND password = '{$password}'   LIMIT 1";
        
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

        $properties = $this->clean_properties();

        //insert sql
        $sql = "INSERT INTO " .self::$db_table ."(" . implode(",",array_keys($properties)) .")";
        $sql .= "VALUES ('" . implode("','",array_values($properties)) . "')";

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

        $properties = $this->clean_properties();

        $properties_pairs = array();

        foreach ($properties as $key => $value) {
            # code...
            $properties_pairs[] = "{$key}='" . $database->escape_string($value) . "'";
        }

        $sql = "UPDATE " .self::$db_table ." SET ";
        $sql .= implode(", ", $properties_pairs);
        $sql .= "WHERE id=" . $database->escape_string($this->id);

        $database->query($sql);

        return (mysqli_affected_rows($database->connection) == 1) ? true : false;
        
    }

    //delete method

    public function delete(){

        global $database;

        $sql = "DELETE FROM " .self::$db_table ." ";
        $sql .= "WHERE id=" . $database->escape_string($this->id) . " LIMIT 1";

        $database->query($sql);

        return (mysqli_affected_rows($database->connection) == 1) ? true : false;

    }

    //end CRUD


}

?>