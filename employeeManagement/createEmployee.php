<?php
  //Autor der Datei Tamara Romer
    include_once '../includes/loginHeader.inc.php';
    include_once '../includes/projectRoleHeader.inc.php';
    include_once '../menu/employeeManagementMenu.php';

?>
<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <link rel="stylesheet" href="../css/style.css">
       <title>Mitarbeiter erstellen</title>
    </head>
    <body>
      <br>
      <br>
    <form action="includes/employeeManagement_Script.inc.php" method="POST" >
    <h3>Bitte füllen sie die Felder aus</h3>

    <table class="formTable">
            <tbody>
                <tr>
                    <td>Vorname:</td>
                    <td><input type="text" size="30" name="firstname" placeholder="Vorname" minlength="2" maxlength="20" required></td>
                </tr>
                <tr>
                    <td>Nachname:</td>
                    <td><input type="text" size="30" name="lastname" placeholder="Nachname" minlength="2" maxlength="20" required></td>
                </tr>
                <tr>
                    <td>Passwort:</td>
                    <td><input type="password" size="30" name="password" placeholder="Passwort" minlength="8" maxlength="20" required></td>
                </tr>
                <tr>
                    <td>Wiederholen Sie das Passwort:</td>
                    <td><input type="password" size="30" name="passwordRepeat" placeholder="Passwort wiederholen" minlength="8" maxlength="20" required></td>
                </tr>
                <tr>
                    <td>Kernarbeitszeit von:</td>
                    <td><input type="time" name="coreTimeFrom" required></td>
                </tr>
                <tr>
                    <td>Kernarbeitszeit bis:</td>
                    <td><input type="time" name="coreTimeTo" required></td>
                </tr>
                <tr>
                    <td>Einstelldatum:</td>
                    <td><input type="date" name="hiringDate" required></td>
                </tr>
                <tr>
                    <td>Wochenarbeitsstunden:</td>
                    <td><input type="text" size="30" name="weeklyWorkingHours" placeholder="Wochenarbeitstsunde" required></td>
                </tr>
                <tr>
                    <td>Projektleiter</td>
                    <td><input type="checkbox" name="projectManager"></td>
                </tr>
            </tbody>
        </table>

        <input type="submit" name="button_createEmployee" value = "Erstellen">
    </form>
    <br>


    <?php
    //Anzeige der Fehlermeldeung
    if(isset($_GET["error"])){
        if($_GET["error"] == "invalidFirstname") {
            echo "<p>Der Vorname ist nicht zulässig. Der Name darf nur aus Buchstaben bestehen!</p>";
        }
        elseif ($_GET["error"] == "invalidLastname") {
            echo "<p>Der Nachname ist nicht zulässig. Der Name darf nur aus Buchstaben bestehen!</p>";
        }
        elseif ($_GET["error"] == "invalidPassword") {
            echo "<p>Das Passwort ist nicht zulässig! Es benötigt mindestens 8 Zeichen, einen Großbuchstaben, einen Kleinbuchstaben, eine Zahl und ein Sonderzeichen!</p>";
        }
        elseif ($_GET["error"] == "passwordsDontMatch") {
            echo "<p>Die eingegebenen Passwörter stimmen nicht überein!</p>";
        }
        elseif ($_GET["error"] == "invalidCoreTime") {
            echo "<p>Die Kernarbeitszeit ist unzulässig! Der Beginn der Kernarbeitszeit kann nicht nach dem Ende sein!</p>";
        }
        elseif ($_GET["error"] == "invalidWeeklyWorkingHours") {
            echo "<p>Die Wochenarbeitsstunden muss größer 10 sein!</p>";
        }
        elseif ($_GET["error"] == "stmtfailed") {
            echo "<p>Etwas ist schief gelaufen!</p>";
        }
        elseif ($_GET["error"] == "none") {
            echo "<p>Der Mitarbeiter wurde erfolgreich angelegt!</p>";
        }
    }
    ?>
    </body>
</html>
