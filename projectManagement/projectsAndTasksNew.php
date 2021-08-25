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
                          <td><textarea name="projectname"  maxlength="50" cols="50"></textarea></td>
                      </tr>
                      <tr>
                          <td>Starttermin:</td>
                          <td><input type="date" name="beginDate"></td>
                      </tr>
                      <tr>
                          <td>Anzahl der Aufgaben</td>
                          <td><input type="number" name="amountTasks"></td>
                      </tr>
                  </tbody>
              </table>

            <input type="submit" name="button_createProject" value="Aufgaben anlegen">
         </form>

         <?php
         if(isset($_GET["error"])){
             if($_GET["error"] == "emptyInput"){
                 echo "<p>Bitte befüllen Sie alle Pflichtfelder(*)</p>";
             }
             elseif ($_GET["error"] == "emptyInput") {
                 echo "<p>Bitte geben sie einen Projektnamen und ein Starttermin an!</p>";
             }
             elseif ($_GET["error"] == "none") {
                 echo "<p>Das Projekt wurde erfolgreich angelegt!</p>";
             }

         }

         ?>



    </body>

</html>
