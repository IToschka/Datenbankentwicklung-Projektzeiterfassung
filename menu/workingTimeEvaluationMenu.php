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
