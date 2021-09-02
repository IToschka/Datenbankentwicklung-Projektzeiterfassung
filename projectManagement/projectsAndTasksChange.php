<?php
    include_once '../menu/projectsAndTasksMenu.php';
?>

<!DOCTYPE html>
<html>

    <head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="../css/style.css">
   <title>Bestehendes Projekt ändern</title>
    </head>

    <body>
        <form method="post" action="projectsAndTasksNew.html"> <!--php Methode, die die html Seite aufruft und sie mit den Werten des ausgewählten Projekts vorausfüllt und bestimmte Werte unabänderbar macht--></form>
        <p>ProjektID:<input type="number" name="ProjectID" ></p>
         <input type="submit" name="button_change" value="Bestätigen">
        <input type="submit" name="button_projectManagerMenu" value="Zurück zum Hauptmenü">
    </body>

</html>
