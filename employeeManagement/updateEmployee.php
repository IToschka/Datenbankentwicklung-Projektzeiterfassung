<?php
  //Autor Tamara Romer
  include_once '../includes/loginHeader.inc.php';
  include_once '../menu/employeeManagementMenu.php';
?>
<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <link rel="stylesheet" href="../css/style.css">
       <title>Mitarbeiter ändern</title>
    </head>
    <body>
      <form action="includes/employeeManagement_Script.inc.php" method="POST">
        <table class="formTable">
            <tbody>
                <tr>
                    <td>Personalnummer:</td>
                    <td><input type="text" name="pnr" placeholder="Personalnummer" maxlength="6" required></td>
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
        <input type="submit" name="button_updateEmployee" value="Ändern">
    </form>
    <br>
    <form action="../includes/footer.inc.php" method="POST" >
      <input type="submit" name="button_BackToMenu" value="Zurück zum Menü">
      <input type="submit" name="button_LogOut" value = "Abmelden">
    </form>

    <?php
    //Anzeige der Fehlermeldeung
    if(isset($_GET["error"])){
        if($_GET["error"] == "pnrNotExists") {
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
