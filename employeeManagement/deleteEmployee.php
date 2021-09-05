<?php
    include_once '../menu/employeeManagementMenu.php';
?>
<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <link rel="stylesheet" href="../css/style.css">
       <title></title>
    </head>

    <body>

    <form action="includes/employeeManagement_Script.inc.php" method="POST">
    <table>
            <tbody>
                <tr>
                    <td>Personalnummer:</td>
                    <td><input type="text" name="pnr" placeholder="Personalnummer"  maxlength="6" required></td>
                </tr>

            </tbody>
        </table>

        <input type="submit" name="button_backToMenu" value="Zurück zum Menü">
        <input type="submit" name="button_deleteEmployee" value="Löschen">
    </form>

    <?php
    if(isset($_GET["error"])){
        if($_GET["error"] == "pnrNotExists"){
            echo "<p>Die Personalnummer existiert nicht!</p>";
        }
        elseif ($_GET["error"] == "stmtfailed") {
            echo "<p>Etwas ist schief gelaufen!</p>";
        }
        elseif ($_GET["error"] == "none") {
            echo "<p>Der Mitarbeiter wurde erfolgreich gelöscht!</p>";
        }
    }

    ?>
    </body>
</html>
