<?php
    //Autor der Datei Katja Frei

    include_once '../menu/projectsAndTasksMenu.php';
    

?>

<!DOCTYPE html>
<html>

    <head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="../css/style.css">
   <title>Bestehendes Projekt löschen</title>
    </head>

    <body>

    <form action="includes/projectAndTasksScript.inc.php" method="POST" >
        <p>ProjektID:<input type="number" name="projectID" required ></p>
            <input type="submit" name="button_delete" value="Bestätigen">
            </form>


            <?php
    if ($_GET["error"] == "invalidProjectId") {
        echo "<p>Die eingegeben Projek ID existiert nicht!</p>";
    }
    elseif ($_GET["error"] == "noAccess") {
        echo "<p>Die PNR des Projektleiters darf nur nummerische Werte enthalten!</p>";
    }
 
            ?>

</body>
</html>