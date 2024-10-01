<?php

require_once("new_config.php");

class Database {

    public $connection;

    function __construct() {
        $this->open_db_connection();
    }

    // Open a database connection
    public function open_db_connection() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

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

    // Query method with support for prepared statements
    public function query($sql, $types = null, $params = []) {
        $stmt = $this->connection->prepare($sql);
        if ($types && $params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $this->confirm_query($result);
        return $result;
    }

    // Check if the query succeeded
    private function confirm_query($result) {
        if (!$result && $this->connection->error) {
            die("Query failed: " . $this->connection->error);
        }
        return $result;
    }

    // Escape string method for non-prepared queries (if needed)
    public function escape_string($value) {

        if ($value === null) {
            # code...
            return ''; //Return an empty string if the value is null
        }

        return $this->connection->real_escape_string($value);
    }

    // Get the last inserted ID
    public function the_insert_id() {
        return $this->connection->insert_id;
    }

    // Get the affected rows for updates/deletes
    public function affected_rows() {
        return $this->connection->affected_rows;
    }
}

// Instantiate the database object globally
$database = new Database();

?>
