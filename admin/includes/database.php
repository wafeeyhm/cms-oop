<?php

require_once("new_config.php");

class Database{

    public $connection;

    function __construct(){
        $this->open_db_connection();
    }

    // Open a database connection
    public function open_db_connection(){

        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Check for connection errors
        if ($this->connection->connect_errno) {
            throw new Exception("Database connection failed: " . $this->connection->connect_error);
        }

    }

    // Close the database connection
    public function close_db_connection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    public function query($sql){

        $this->connection->prepare($sql);

        $result = $this->connection->query($sql);

        $this->confirm_query($result);

        return $result;
    }

    private function confirm_query($result){

        if (!$result) {
            # code...
            die("Query failed" . $this->connection->error);
        }

        return $result;
    }

    // Escape string (for cases where prepared statements can't be used)
    public function escape_string($value) {

        return $this->connection->real_escape_string($value);
        
    }

    public function the_insert_id(){
        return $this->connection->insert_id;
    }

}

// Instantiate the database class
$database = new Database();

?>