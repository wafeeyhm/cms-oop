<?php

    require_once("init.php");

    if($session->is_signed_in()){
        redirect("index.php");
    }

    if(isset($_POST['submit'])){

        $username = trim($_POST['username']); 
        $password = trim($_POST['password']); 

        //method to check database user
        $user_found = User::verify_user($username, $password);

        //check for user found
        if($user_found){
            $session->login($user_found);
            redirect("index.php");
        }
        else{
            $message = "Incorrect username or password";
        }

    }
    else{

        $username = "";
        $password = "";

    }

?>