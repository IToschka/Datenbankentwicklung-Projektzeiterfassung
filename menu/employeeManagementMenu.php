<?php
//Autor der Datei Katja Frei
include_once '../includes/loginHeader.inc.php';
include_once '../includes/projectRoleHeader.inc.php';
 ?>

<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <link rel="stylesheet" href="../css/style.css">
       <title></title>
    </head>

    <body>
    <center> 
    <h1>Mitarbeiterverwaltung</h1>
    <nav>
    <ul>
      <li><a href="../employeeManagement/createEmployee.php">Mitarbeiter erfassen </a></li>
      <li><a href="../employeeManagement/updateEmployee.php">Mitarbeiter ändern</a></li>
      <li><a href="../employeeManagement/deleteEmployee.php">Mitarbeiter löschen </a></li>
    </ul>
    </nav>

    <!--Buttons für zurück ins Menu und Abmeldung-->
    <form action="../includes/footer.inc.php" method="POST" >
    <input type="submit" name="button_BackToMenu" value="Zurück zum Menü">
    <input type="submit" name="button_LogOut" value = "Abmelden">
    </form>
    </center> 

    </body>
</html>
