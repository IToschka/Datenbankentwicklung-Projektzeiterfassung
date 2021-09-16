<?php
//Autor der Datei Tamara Romer
    session_start();
    if (!isset($_SESSION['pnr'])){
        header ('Location: http://localhost/Datenbankentwicklung-Projektzeiterfassung/login.php');
    }
?>
