<?php
//Autor der Datei Tamara Romer
session_start();
        if (isset($_POST['button_BackToMenu'])){
          echo "gedrückt";
          if (isset($_SESSION['projectManager'])){
            header("location: ../menu/projectManagerMenu.php");
          }else{
            header("location: ../menu/employeeMenu.php");
          }
        }

        if (isset($_POST['button_LogOut'])){
          echo "Wurde gedrückt";
          session_start();
          session_unset();
          session_destroy();
          header ('Location: http://localhost/Datenbankentwicklung-Projektzeiterfassung/login.php');
        }

?>
