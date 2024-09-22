<?php

    require_once("includes/header.php");

    $session->logout();
    redirect("login.php");

    require_once("includes/footer.php");

?>
