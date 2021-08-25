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
                    <td>Vorname*:</td>
                    <td><input type="text" name="firstname" placeholder="Vorname"></td>
                </tr>
                <tr>
                    <td>Nachname*:</td>
                    <td><input type="text" name="lastname" placeholder="Nachname"></td>
                </tr>
                <tr>
                    <td>Passwort*:</td>
                    <td><input type="password" name="password" placeholder="Passwort"></td>
                </tr>
                <tr>
                    <td>Kernarbeitszeit von*:</td>
                    <td><input type="time" name="coreTimeFrom"></td>
                </tr>
                <tr>
                    <td>Kernarbeitszeit bis*:</td>
                    <td><input type="time" name="coreTimeTo"></td>
                </tr>
                <tr>
                    <td>Einstelldatum*:</td>
                    <td><input type="date" name="hiringDate"></td>
                </tr>
                <tr>
                    <td>Wochenarbeitsstunden*:</td>
                    <td><input type="text" name="weeklyWorkingHours" placeholder="Wochenarbeitstsunde"></td>
                </tr>
                <tr>
                    <td>Projektleiter</td>
                    <td><input type="checkbox" name="projectManager"></td>
                </tr>
            </tbody>
        </table>

        <input type="submit" name="button_EmployeeMenu" value="Zurück zum Menü">
        <input type="submit" name="button_create" value = "Erstellen">
    </form>

    <?php
    if(isset($_GET["error"])){
        if($_GET["error"] == "emptyInput"){
            echo "<p>Bitte befüllen Sie alle Pflichtfelder(*)</p>";
        }
        elseif ($_GET["error"] == "invalidFirstname") {
            echo "<p>Der Vorname ist nicht zulässig. Der Name darf nur aus Buchstaben bestehen und muss mindestens zwei Zeichen enthalten!</p>";
        }
        elseif ($_GET["error"] == "invalidLastname") {
            echo "<p>Der Nachname ist nicht zulässig. Der Name darf nur aus Buchstaben bestehen und muss mindestens zwei Zeichen enthalten!</p>";
        }
        elseif ($_GET["error"] == "invalidPassword") {
            echo "<p>Das Passwort ist nicht zulässig! Es benötigt mindestens 8 Zeichen, einen Großbuchstaben, einen Kleinbuchstaben, eine Zahl und ein Sonderzeichen!</p>";
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
