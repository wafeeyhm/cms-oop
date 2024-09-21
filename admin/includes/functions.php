<?php
    // Use spl_autoload_register to autoload classes
    spl_autoload_register(function($class) {
        
        // Convert class name to lowercase
        $class = strtolower($class);
        
        // Define the path to the file
        $path = "includes/{$class}.php";
        
        // Check if the file exists and include it
        if (file_exists($path)) {
            require_once($path);
        } else {
            die("This file name {$class}.php was not found");
        }

    });
?>
