<?php

    if (!isset($_SESSION['projectManager'])){
        header('Location:  http://localhost/Datenbankentwicklung-Projektzeiterfassung/menu/employeeMenu.php');
        exit;
    }
