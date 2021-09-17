<?php
    //Autor: Katja Frei
    include_once '../menu/projectsAndTasksMenu.php';
    

?>

<!DOCTYPE html>
<html>

    <head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="../css/style.css">
   <title>Bestehendes Projekt kopieren</title>
    </head>

    <body>

    <form action="includes/projectAndTasksScript.inc.php" method="POST" >
        <p>ProjektID:<input type="number" name="projectID" required ></p>
            <input type="submit" name="button_delete" value="Bestätigen">
            </form>


            <?php
 if(isset($_GET["error"])){
    if ($_GET["error"] == "invalidDate") {
        echo "<p>Das angegebene Datum liegt in der Vergangenheit!</p>";
    }
elseif ($_GET["error"] == "none") {
    echo "<p>Das Projekt wurde erfolgreich gelöscht!</p>";
}
 }
            ?>

</body>
</html>