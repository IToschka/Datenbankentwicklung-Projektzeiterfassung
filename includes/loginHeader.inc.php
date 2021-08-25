<?php
    session_start();
    if (!isset($_SESSION['Username'])){
        header ('Location: http://localhost:8080/Datenbankentwicklung-Projektzeiterfassung/login.php')
        exit;
    }
?>
