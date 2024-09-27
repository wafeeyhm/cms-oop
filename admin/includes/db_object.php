<?php

class Db_object{


    public static function find_all(){
        
        return static::run_query("SELECT * FROM " .static::$db_table ." ");

    }

    public static function find_by_id($user_id){
        
        $result_array =  static::run_query("SELECT * FROM " .static::$db_table ." WHERE id=$user_id LIMIT 1");

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
        $sql = "INSERT INTO " .static::$db_table ."(" . implode(",",array_keys($properties)) .")";
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

        $sql = "UPDATE " .static::$db_table ." SET ";
        $sql .= implode(", ", $properties_pairs);
        $sql .= "WHERE id=" . $database->escape_string($this->id);

        $database->query($sql);

        return (mysqli_affected_rows($database->connection) == 1) ? true : false;
        
    }

    //delete method

    public function delete(){

        global $database;

        $sql = "DELETE FROM " .static::$db_table ." ";
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
            $object_array[] = static::instantiation($row);
        }

        return $object_array;
    }

    public static function instantiation($record){

        $calling_class = get_called_class();

        $object = new $calling_class;

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

    protected function properties(){

        // return get_object_vars($this);

        $properties = array();

        foreach (static::$db_table_fields as $db_field) {
            # code...
            if (property_exists($this, $db_field)) {

                # code...
                $properties[$db_field] = $this->$db_field;
            }
        }

        return $properties;

    }

    protected function clean_properties(){

        global $database;

        $clean_properties = array();

        foreach ($this->properties() as $key => $value) {
            # code...
            $clean_properties[$key] = $database->escape_string($value);
        }

        return $clean_properties;

    }

}

?>