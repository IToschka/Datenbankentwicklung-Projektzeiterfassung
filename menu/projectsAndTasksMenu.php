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
   <title>Erfassungsbereich Projekte & Projektaufgaben </title>
    </head>

    <body>
    <center>
    <h1>Erfassungsbereich Projekte & Projektaufgaben</h1>
    <nav>
    <ul>
      <li><a href="../projectManagement/projectsAndTasksNew.php"> Neues Projekt anlegen </a></li>
      <li><a href="../projectManagement/projectsAndTasksCopy.php">Projekt kopieren</a></li>
      <li><a href="../projectManagement/projectsAndTasksChange.php">Projekt ändern</a></li>
      <form action="../includes/footer.inc.php" method="POST" >
      <li style="margin-right: 10px"><input type="submit" name="button_BackToMenu" value="Zurück zum Menü"></li>
      <li><input type="submit" name="button_LogOut" value = "Abmelden"></li>
      </form>
    </ul>
    </nav>

    </center>

    </body>

</html>
