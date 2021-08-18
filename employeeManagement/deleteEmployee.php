<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <title></title>
    </head>

    <body>
    <h1>Mitarbeiter löschen</h1>
    <form action="includes/employeeQueries.inc.php" method="POST">
    <table>
            <tbody>
                <tr>
                    <td>Personalnummer:</td>
                    <td><input type="text" name="pnr" placeholder="Personalnummer"></td>
                </tr>

            </tbody>
        </table>

        <input type="submit" name="button_EmployeeMenu" value="Zurück zum Menü">
        <input type="submit" name="button_delete" value="Löschen">
    </form>

    <?php
    if(isset($_GET["error"])){
        if($_GET["error"] == "emptyInput"){
            echo "<p>Bitte geben Sie eine Personalnummer ein!</p>";
        }
        elseif($_GET["error"] == "pnrNotExists"){
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
