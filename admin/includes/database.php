<?php

require_once("new_config.php");

class Database{

    public $connection;

    function __construct(){
        $this->open_db_connection();
    }

    public function open_db_connection(){

        $this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if(mysqli_connect_errno()){
            die("Database connection failed");
        }

    }

    public function query($sql){
        $result = mysqli_query($this->connection, $sql);

        
    }

    private function confirm_query($result){
        if (!$result) {
            # code...
            die("Query failed");
        }

        return $result;
    }

    public function escape_string($sql){
        $escaped = mysqli_real_escape_string($this->connection, $sql);

        return $escaped;
    }

}

$database = new Database();

?>