<?php
    include_once '../menu/projectsAndTasksMenu.php';
?>s
<!DOCTYPE html>
<html>

    <head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="../css/style.css">
   <title>Projekt aus bestehendem Projekt anlegen</title>
    </head>

    <body>
    <form method="post" action="projectAndTaskScript.inc.php">
   <p>ProjektID:<input type="number" name="ProjectID" ></p>
    <input type="submit" name="button_enterProject" value="Bestätigen">
    <input type="submit" name="button_projectManagerMenu" value="Zurück zum Hauptmenü">
    </form>
</body>

</html>
