<?php

    function autoLoader($class){
        // Convert class name to lowercase
        $class = strtolower($class);
        
        // Define the path to the file
        $path = "includes/{$class}.php";

        if (is_file($path) && !class_exists($class)) {
            # code...
            include $path;
        }
        else{
            die("This file name {$class}.php was not found");
        }
    }

    // Use spl_autoload_register to autoload classes
    spl_autoload_register("autoLoader");
?>
