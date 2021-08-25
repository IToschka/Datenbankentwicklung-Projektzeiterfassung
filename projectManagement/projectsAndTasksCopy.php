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
    <form method="post" action="projectsAndTasksNew.html"> <!--php Methode, die die html Seite aufruft und sie mit den Werten des ausgewählten Projekts vorausfüllt--></form>
   <p>ProjektID:<input type="number" name="ProjectID" ></p>
    <input type="submit" name="confirm" value="Bestätigen">
    </form>
</body>

</html>
