<?php
   //Autor: Katja Frei
   include_once '../menu/projectsAndTasksMenu.php';
    

?>

<!DOCTYPE html>
<html>

    <head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="../css/style.css">
   <title>Bestehendes Projekt kopieren</title>
    </head>

    <body>

        <?php include_once '../includes/dbh.inc.php';
                include_once 'includes/projectManagementFunctions.inc.php';
        
                $projectName ="";
        if(isset($_POST['button_choose'])) {
        

        $projectID = $_POST['projectID'];
        
        // Projekt mit Daten füllen
        fillProject($conn, $projectID); 
        $projectName = $_SESSION['projectName'];
        $beginDate = $_SESSION['beginDate'];

        $tasks = "";
        // Aufgaben mit Daten füllen
        fillTasks($conn, $projectID);
        $tasks = $_SESSION['tasks'];

        }
                
                     ?>
         <!-- Ruft sich selbst auf -->            
        <form method="post" action="projectsAndTasksCopy.php">
        <p>ProjektID:<input type="number" name="projectID" required min="1"></p>
            <input type="submit" name="button_choose" value="Bestätigen">
            </form>

        <br>
        <br>
       <form method="POST" action="includes/projectAndTasksSkript.inc.php">
        <table>
                  <tbody>
                      <tr>
                          <td>Projekttitel:</td>
                          <td><textarea name="projectName"  maxlength="50" cols="50"><?php echo $projectName; ?></textarea></td>
                      </tr>
                      <tr>
                          <td>Starttermin:</td>
                          <td><input type="date" name="beginDate" value='<?php  echo $beginDate; ?>'></td>
                      </tr>
                      
                      <tr>
                      <td>PNR Projektleiter:</td>
                      <td><input type="text" texarea readonly ="readonly" name="projectManager" value= <?php $projectManager = $_SESSION['pnr']; echo $projectManager ?> ></td> 
                      
                      </tr>

    <?php 
            //Aufgaben füllen je nach dem wie lang das task array ist
            $tasks = $_SESSION['tasks'];
            if ($tasks != null) {
               for($i=0; $i < count($tasks); $i++) { ?>          
                <tr> <td><?php
                $i2 = $i + 1;
                echo "Aufgabe $i2:"; ?></td>
                    <?php
                    echo '<td> <textarea name="task'.$i.'" maxlength="2000" cols="50" required>'.$tasks[$i].'</textarea></td>';
               }}?>
                </tr>

                  </tbody>
              </table>

            <input type="submit" name="button_copyProject" value="Projekt speichern">
            <input type="submit" name="button_createTasks" value="Aufgaben speichern">
            
         </form>
         </body>

         <?php

        if(isset($_GET["error"])){
             if ($_GET["error"] == "invalidProjectID") {
                 echo "<p>Zur angegebenen Projekt ID besteht kein Projekt!</p>";
             } 
            elseif ($_GET["error"] == "noAccess") {
                echo "<p>Die PNR des Projektleiters muss darf nur nummerische Werte enthalten!</p>";
            }
            if ($error == "invalidDate") {
                echo "<p>Das angegebene Datum liegt in der Vergangenheit!</p>";
            } 
            elseif ($error == "titleAlreadyExists") {
                echo "<p>Ein Projekt mit diesem Titel existiert bereits!</p>";
            }
             elseif ($error == "none") {
                 echo "<p>Das Projekt wurde erfolgreich angelegt!</p>";
             }

         }

         ?>



</html>
