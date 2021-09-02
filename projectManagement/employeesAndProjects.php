<!DOCTYPE html>
<html>

    <head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="../css/style.css">
   <title>Mitarbeiter </title>
    </head>

    <body>
        <h1>Erfassungsbereich der Projektmitarbeiter</h1>
        <form method="post" action="includes/projectAndTasksScript.inc.php">
            <table>
                <tbody>
                    <tr> 
            <td>Personalnummer:</td>
            <td><input type="text" name="pnr" required></td>
            </tr>
            <tr>
            <td>ProjektID:</td>
            <td><input type="number" name="projectID" required></td>
            </tr>
                </tbody>
            </table>
            <input type="submit" name="button_connect" value="Zuordnen"> 
            <input type="submit" name="button_disconnect" value="Trennen"> 
            <input type="submit" name="button_projectManagerMenu" value="Zurück zum Hauptmenü">
        
        </form>

        <?php

if(isset($_GET["error"])){
    if ($_GET["error"] == "invalidpnr") {
        echo "<p>Diese PNR existiert nicht!</p>";
    }
  elseif ($_GET["error"] == "invalidProjectID") {
       echo "<p>Es exisitert kein Projekt zu dieser ID!</p>";
   }
   elseif ($_GET["error"] == "none") {
    echo "<p>Der Mitarbeiter wurde erfolgreich dem Projekt zugeordnet!</p>";
}
elseif ($_GET["error"] == "numericPNR") {
    echo "<p>Die PNR darf nur numerische Werte enthalten!</p>";
}
    elseif ($_GET["error"] == "none1") {
        echo "<p>Der Mitarbeiter wurde erfolgreich vom Projekt getrennt!</p>";
}
    elseif ($_GET["error"] == "none1") {
        echo "<p>Der Mitarbeiter wurde erfolgreich vom Projekt getrennt!</p>";
}
}

        ?>
    
</body>

</html>