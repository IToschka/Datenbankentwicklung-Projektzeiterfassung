<?php

    session_start();
    if (!isset($_SESSION['projectRole'])){
        header('Location: http://localhost:8080/Datenbankentwicklung-Projektzeiterfassung/startMenu/employeeMenu.php');
        exit;
    }
