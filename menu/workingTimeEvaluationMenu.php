<?php
//Autor der Datei Tamara Romer

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
    <h1>Auswertung der Arbeitszeiten</h1>
    <nav>
    <ul>
      <li><a href="../workingTimeEvaluation/evaluationTotalAndPerProject.php">Projektbezogene Auswertung</a></li>
      <li><a href="../workingTimeEvaluation/evaluationPerEmployee.php">Mitarbeiterbezogene Auswertung</a></li>
      <form action="../includes/footer.inc.php" method="POST" >
      <li style="margin-right: 10px"><input type="submit" name="button_BackToMenu" value="Zurück zum Menü"></li>
      <li><input type="submit" name="button_LogOut" value = "Abmelden"></li>
      </form>
    </ul>
    </nav>

    </center>

    </body>
</html>
