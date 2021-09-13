<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <title>Footer</title>
    </head>

    <body>
      <input type="submit" name="button_BackToMenu" value="Zurück zum Menü">
      <input type="submit" name="button_LogOut" value = "Abmelden">
        <?php
        if (isset($_POST['button_BackToMenu'])){
          echo "Button wurde geklickt";
          if (isset($_SESSION['projectManager'])){
            header("location: ../menu/projectManagerMenu.php");
            exit();
          }else{
            header("location: ../menu/employeeMenu.php");
            exit();
          }
        }

        if (isset($_POST['LogOut'])){
          session_start();
          session_unset();
          session_destroy();
          header ('Location: http://localhost:8080/Datenbankentwicklung-Projektzeiterfassung/login.php');
          exit;
        }

        ?>

    </body>
</html>
