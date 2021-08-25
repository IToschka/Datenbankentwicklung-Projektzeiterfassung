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
    <form action="includes/employeeQueries.inc.php" method="POST">
    <table>
            <tbody>
                <tr>
                    <td>Personalnummer:</td>
                    <td><input type="text" name="pnr" placeholder="Personalnummer"></td>
                </tr>
                <tr>
                    <td>Kernarbeitszeit von:</td>
                    <td><input type="time" name="coreTimeFrom"></td>
                </tr>
                <tr>
                    <td>Kernarbeitszeit bis:</td>
                    <td><input type="time" name="coreTimeTo"></td>
                </tr>
                <tr>
                    <td>Wochenarbeitsstunden:</td>
                    <td><input type="text" name="weeklyWorkingHours" placeholder="Wochenarbeitstsunde"></td>
                </tr>
            </tbody>
        </table>

        <input type="submit" name="button_EmployeeMenu" value="Zurück zum Menü">
        <input type="submit" name="button_update" value="Ändern">

    </form>


    <?php
    if(isset($_GET["error"])){
        if($_GET["error"] == "emptyInput"){
            echo "<p>Bitte geben Sie eine Personalnummer ein!</p>";
        }
        elseif ($_GET["error"] == "pnrNotExists") {
            echo "<p>Die Personalnummer existiert nicht!</p>";
        }
        elseif ($_GET["error"] == "invalidWeeklyWorkingHours") {
            echo "<p>Die Wochenarbeitsstunden muss größer 10 sein!</p>";
        }
        elseif ($_GET["error"] == "stmtfailed") {
            echo "<p>Etwas ist schief gelaufen!</p>";
        }
        elseif ($_GET["error"] == "none") {
            echo "<p>Der Mitarbeiter wurde erfolgreich geändert!</p>";
        }

    }

    ?>

    </body>
</html>
