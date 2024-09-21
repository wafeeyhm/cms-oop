<?php

require_once("new_config.php");

class Database{

    public $connection;

    function __construct(){
        $this->open_db_connection();
    }

    public function open_db_connection(){

        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->connection->connect_errno) {
            # code...
            die("Database connection failed" . $this->connection->connect_error);
        }

    }

    public function query($sql){
        $result = $this->connection->query($sql);

        return $result;
    }

    private function confirm_query($result){

        if (!$result) {
            # code...
            die("Query failed" . $this->connection->error);
        }

        return $result;
    }

    public function escape_string($sql){

        $escaped = $this->connection->real_escapae_string($sql);

        return $escaped;
    }

    public function the_insert_id(){
        return $this->connection->insert_id;
    }

}

$database = new Database();

?>