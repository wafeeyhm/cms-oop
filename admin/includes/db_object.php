<?php

class Db_object{

    protected static $db_table = "users";
    protected static $db_table_fields = array('username', 'password', 'first_name', 'last_name');
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;

    public static function find_all(){
        
        return self::run_query("SELECT * FROM " .self::$db_table ." ");

    }

    public static function find_by_id($user_id){
        
        $result_array =  self::run_query("SELECT * FROM " .self::$db_table ." WHERE id=$user_id LIMIT 1");

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

    protected function properties(){

        // return get_object_vars($this);

        $properties = array();

        foreach (self::$db_table_fields as $db_field) {
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