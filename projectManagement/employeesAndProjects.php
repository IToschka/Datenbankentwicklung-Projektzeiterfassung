<?php
//Autor der Datei Katja Frei


include_once '../includes/loginHeader.inc.php'

?>

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
                    <td><input type="number" name="pnr" required min="1"></td>
                    </tr>
                    <tr>
                    <td>ProjektID:</td>
                    <td><input type="number" name="projectID" required min="1"></td>
                    </tr>
    </tbody>
            </table>
            <input type="submit" name="button_connect" value="Zuordnen"> 
            <input type="submit" name="button_disconnect" value="Trennen"> 
        
        </form>


        <?php

if(isset($_GET["error"])){
    if ($_GET["error"] == "invalidpnr") {
        echo "<p>Diese PNR existiert nicht!</p>";
    }
  elseif ($_GET["error"] == "invalidProjectID") {
       echo "<p>Es exisitert kein Projekt zu dieser ID!</p>";
   }
   elseif ($_GET["error"] == "noSuchCombination") {
    echo "<p>Die Kombination von Mitarbeiter und Projekt existiert nicht!</p>";
}
elseif ($_GET["error"] == "alreadyExisting") {
    echo "<p>Die Kombination aus Projekt und Mitarbeiter existiert bereits!</p>";
}
   elseif ($_GET["error"] == "none") {
    echo "<p>Der Mitarbeiter wurde erfolgreich dem Projekt zugeordnet!</p>";
}
    elseif ($_GET["error"] == "none1") {
        echo "<p>Der Mitarbeiter wurde erfolgreich vom Projekt getrennt!</p>";
}
}

        ?>

<form action="../includes/footer.inc.php" method="POST" >
            <input type="submit" name="button_BackToMenu" value="Zurück zum Menü">
            <input type="submit" name="button_LogOut" value = "Abmelden">
            </form>
    
    </body>

</html>