<?php
//Autor: Katja Frei
    include_once '../menu/projectsAndTasksMenu.php';
    

?>

<!DOCTYPE html>
<html>

    <head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="../css/style.css">
   <title>Bestehendes Projekt ändern</title>
    </head>

    <body>

        <?php include_once '../includes/dbh.inc.php';
                include_once 'includes/projectManagementFunctions.inc.php';
        
        $projectName = "";
        if(isset($_POST['button_choose'])) {
         $projectID = $_POST['projectID']; 

    
           
           /* if(invalidProjectID($conn, $projectID) !== false) {
                header("location: ../projectsAndTasksChange.php?error=invalidProjectID");
                exit();
            } 
         if(noNegativeProjectID($projectID) !== false) {
            header("location: ../projectsAndTasksChange.php?error=noNegativeProjectID");
            exit();
         }
         
         if(noAccess($conn, $projectID, $projectManager) !== false) {
            header("location: ../projectsAndTasksChange.php?error=noAccess");
            exit(); 
         } */
        
        fillProject($conn, $projectID); 
        $projectName = $_SESSION['projectName'];
        $beginDate = $_SESSION['beginDate'];

        }
                
                     ?>
        <form method="post" action="projectsAndTasksChange.php">
        <p>ProjektID:<input type="number" name="projectID" required ></p>
            <input type="submit" name="button_choose" value="Bestätigen">
            <input type="submit" name="button_projectManagerMenu" value="Zurück zum Hauptmenü">
            </form>
        <br>
        <br>
       <form method="POST" action="projectsAndTasksChange.php">
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
        if(isset($_POST['button_addTask'])) {
            $projectID = $_POST['projectID'];
            $_SESSION['projectID'] = $projectID;
            
            getTaskID($conn, $projectID);
            $projectTaskID = $row['ProjectTaskID'];
            $id = $projectTaskID +1;


            echo '<tr><td>Aufgabe ' .$id. ':</td><td><textarea name="task"  maxlength="2000" cols="50"></textarea></td></tr>';
        }
                ?>
               

                  </tbody>
              </table>
            <input type="submit" name="button_addTask" value="Aufgabe hinzufügen">
            <input type="submit" name="button_change" value="Eingaben speichern">
            
         </form>
         </body>

         <?php

        if(isset($_GET["error"])){
             if ($_GET["error"] == "invalidProjectID") {
                 echo "<p>Zur angegebenen Projekt ID besteht kein Projekt!</p>";
             } 
            elseif ($_GET["error"] == "noAccess") {
                echo "<p>Projektleiter dürfen nur ihre eingenen Projekte ändern!</p>";
            }
            elseif ($_GET["error"] == "invalidDate") {
                echo "<p>Das angegebene Datum liegt in der Vergangenheit!</p>";
            }
             elseif ($_GET["error"] == "none") {
                 echo "<p>Das Projekt wurde erfolgreich geändert!</p>";
             }

         }

         ?>



</html>
