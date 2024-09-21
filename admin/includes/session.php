<?php

class Session{

    private $signed_in = false;
    public $user_id;

    function __construct()
    {
        session_start();
        $this->check_login();
    }

    public function is_signed_in(){
        return $this->signed_in;
    }

    public function login($user){
        if ($user) {
            # code...
            $this->user_id = $_SESSION['user_id'] = $user->id;
            $this->signed_in = true;
        }
    }

    private function check_login(){
        if(isset($_SESSION['user_id'])){
            $this->user_id = $_SESSION['user_id'];
            $this->signed_in = true;
        }
        else{
            $this->signed_in = false;
            unset($this->user_id);
        }
    }

}

$session = new Session();

?>