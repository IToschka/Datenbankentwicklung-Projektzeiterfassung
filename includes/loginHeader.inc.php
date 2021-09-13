<?php
    session_start();
    if (!isset($_SESSION['pnr'])){
        header ('Location: http://localhost/Datenbankentwicklung-Projektzeiterfassung/login.php');
        exit;
    }
?>
