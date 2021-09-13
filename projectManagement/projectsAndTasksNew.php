<?php
    session_start();
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
        <form action="projectsAndTasksNew.php" method="POST" > 
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
                      <td><input type="text" texarea readonly ="readonly" name="projectManager" value= <?php $projectManager = $_SESSION['pnr']; echo $projectManager ?>></td> 
                      <tr>
                          <td>Anzahl der Aufgaben</td>
                          <td><input type="number" name="amountTasks"></td>
                      </tr>
                  </tbody>
              </table>

            <input type="submit" name="button_createProject" value="Eingaben speichern">
            <input type="submit" name="button_projectManagerMenu" value="Zurück zum Hauptmenü">
            
         </form>
         <br>
         <form action="includes/projectAndTasksScript.inc.php" method="POST" > <table> <tbody>
               <?php 
               if(isset($_POST['button_createProject'])) {
               $amountTasks = $_POST['amountTasks'];

               for($i=1; $i <=$amountTasks; $i++) { ?>          
                <tr> <td><?php echo "Aufgabe $i:" ?></td>
                    <td> <textarea name='task' maxlength='50' cols='50' required></textarea></td>
                </tr>
                

       <?php } } ?>
        </tbody> </table> <input type='submit' name='button_createTasks' value='Aufgaben anlegen'> </form>
    <body>

         <?php
        // beim Anlegen des Projekts muss der Projektleiter dem Projekt zugeorndet werden
        //tasks nicht einzeln sondenr wie in Change
        if(isset($_GET["error"])){
             if ($_GET["error"] == "invalidDate") {
                 echo "<p>Das angegebene Datum liegt in der Vergangenheit!</p>";
             }
           elseif ($_GET["error"] == "invalidProjectManagerPNR") {
                echo "<p>Dieser Mitarbeiter ist kein Projektleiter!</p>"; 
            } 
            elseif ($_GET["error"] == "noNegativeAmountTasks") {
                echo "<p>Die PNR des Projektleiters muss darf nur nummerische Werte enthalten!</p>";
            }
             elseif ($_GET["error"] == "none") {
                 echo "<p>Das Projekt wurde erfolgreich angelegt!</p>";
             }

         }

         ?>



    </body>

</html>
