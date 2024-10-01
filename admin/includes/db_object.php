<?php

class Db_object {

    // Abstract method: Must be overridden in child classes
    protected static $db_table;
    protected static $db_table_fields;

    // General find methods
    public static function find_all() {
        return static::run_query("SELECT * FROM " . static::$db_table);
    }

    public static function find_by_id($id) {
        $result_array = static::run_query("SELECT * FROM " . static::$db_table . " WHERE id=? LIMIT 1", 'i', [$id]);
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    // Save (either create or update)
    public function save() {
        return isset($this->id) ? $this->update() : $this->create();
    }

    // Create method using prepared statements
    protected function create() {
        global $database;

        $properties = $this->clean_properties();
        $fields = implode(", ", array_keys($properties));
        $placeholders = implode(", ", array_fill(0, count($properties), "?"));
        $values = array_values($properties);
        $types = $this->get_param_types($values);

        $sql = "INSERT INTO " . static::$db_table . " ($fields) VALUES ($placeholders)";

        if ($database->query($sql, $types, $values)) {
            $this->id = $database->the_insert_id();
            return true;
        } else {
            return false;
        }
    }

    // Update method using prepared statements
    protected function update() {
        global $database;

        $properties = $this->clean_properties();
        $properties_pairs = array();
        $values = array();

        foreach ($properties as $key => $value) {
            $properties_pairs[] = "{$key}=?";
            $values[] = $value;
        }

        // Append 'id' to query
        $values[] = $this->id;
        $types = $this->get_param_types($values);

        $sql = "UPDATE " . static::$db_table . " SET " . implode(", ", $properties_pairs) . " WHERE id=?";

        $database->query($sql, $types, $values);

        return ($database->affected_rows() == 1);
    }

    // Delete method using prepared statements
    public function delete() {
        global $database;

        $sql = "DELETE FROM " . static::$db_table . " WHERE id=? LIMIT 1";
        $database->query($sql, 'i', [$this->id]);

        return ($database->affected_rows() == 1);
    }

    // Execute query with optional prepared statement parameters
    public static function run_query($sql, $types = null, $params = []) {
        global $database;
        $result = $database->query($sql, $types, $params);
        $object_array = [];

        while ($row = $result->fetch_assoc()) {
            $object_array[] = static::instantiation($row);
        }

        return $object_array;
    }

    // Instantiate an object based on the query result
    public static function instantiation($record) {
        $calling_class = get_called_class();
        $object = new $calling_class;

        foreach ($record as $attribute => $value) {
            if ($object->has_the_attribute($attribute)) {
                $object->$attribute = $value;
            }
        }

        return $object;
    }

    // Check if the object has a particular attribute
    private function has_the_attribute($attribute) {
        $object_properties = get_object_vars($this);
        return array_key_exists($attribute, $object_properties);
    }

    // Return an array of object properties (based on $db_table_fields)
    protected function properties() {
        $properties = [];
        foreach (static::$db_table_fields as $db_field) {
            if (property_exists($this, $db_field)) {
                $properties[$db_field] = $this->$db_field;
            }
        }
        return $properties;
    }

    // Clean object properties (sanitize them)
    protected function clean_properties() {
        global $database;
        $clean_properties = [];

        foreach ($this->properties() as $key => $value) {
            
            if ($value === null) {
                # code...
                $clean_properties[$key] = null; // you can assign NULL to be used in SQL Query
            } else {
                # code...
                $clean_properties[$key] = $database->escape_string($value);
            }
             
        }

        return $clean_properties;
    }

    // Get parameter types for prepared statements
    private function get_param_types($values) {
        $types = '';
        foreach ($values as $value) {
            if (is_int($value)) {
                $types .= 'i';
            } elseif (is_double($value)) {
                $types .= 'd';
            } elseif (is_string($value)) {
                $types .= 's';
            } else {
                $types .= 'b'; // blob or other data
            }
        }
        return $types;
    }
}

?>
