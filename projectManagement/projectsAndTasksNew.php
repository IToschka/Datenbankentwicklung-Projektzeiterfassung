<?php
    include_once '../menu/projectsAndTasksMenu.php';
?>
<!DOCTYPE html>
<html>

    <head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="../css/style.css">
   <title>Neues Projekt anlegen </title>
    </head>

    <body>
        <form action="includes/projectAndTasksScript.inc.php" method="POST" > <!--hier muss ein php Skript rein, das die Daten in die DB speichert und zur nächsten Seite weiterleitet -->
          <table>
                  <tbody>
                      <tr>
                          <td>Projekttitel:</td>
                          <td><textarea name="projectname"  maxlength="50" cols="50" required></textarea></td>
                      </tr>
                      <tr>
                          <td>Starttermin:</td>
                          <td><input type="date" name="beginDate" required></td>
                      </tr>
                      <tr>
                      <td>PNR Projektleiter:</td>
                      <td><input type="text" name="projektManager" required></td> 
                      <tr>
                          <td>Anzahl der Aufgaben</td>
                          <td><input type="number" name="amountTasks"></td>
                      </tr>
                  </tbody>
              </table>

            <input type="submit" name="button_createProject" value="Eingaben speichern">
         </form>

         <?php
         if(isset($_GET["error"])){
             if ($_GET["error"] == "invalidDate") {
                 echo "<p>Das angegebene Datum liegt in der Vergangenheit!</p>";
             }
           elseif ($_GET["error"] == "invalidProjectManagerPNR") {
                echo "<p>Dieser Mitarbeiter ist kein Projektleiter!</p>";
            }
            elseif ($_GET["error"] == "numericProjectManagerPNR") {
                echo "<p>Die PNR der Projektleiters muss darf nur nummerische Werte enthalten!</p>";
            }
             elseif ($_GET["error"] == "none") {
                 echo "<p>Das Projekt wurde erfolgreich angelegt!</p>";
             }

         }

         ?>



    </body>

</html>
