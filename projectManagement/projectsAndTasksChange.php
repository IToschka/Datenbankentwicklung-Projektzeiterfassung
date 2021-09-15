<?php
    session_start();
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

        fillTasks($conn, $projectID);
        $tasks = $_SESSION['tasks'];

        }
                
                     ?>
        <form method="post" action="projectsAndTasksChange.php">
        <p>ProjektID:<input type="number" name="projectID" required ></p>
            <input type="submit" name="button_choose" value="Bestätigen">
            <input type="submit" name="button_projectManagerMenu" value="Zurück zum Hauptmenü">
            </form>
        <br>
        <br>
       <form method="POST" action="projectsAndTasksSkript.inc.php">
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

            if ($tasks != null) {
               for($i=0; $i < count($tasks); $i++) { ?>          
                <tr> <td><?php
                $i2 = $i + 1;
                echo "Aufgabe $i2:"; ?></td>
                    <?php
                    echo '<td> <textarea name="task'.$i.'" maxlength="50" cols="50" required>'.$tasks[$i].'</textarea></td>';
               }}?>
                </tr>

                  </tbody>
              </table>

            <input type="submit" name="button_change" value="Eingaben speichern">
            
         </form>
         </body>

         <?php

        if(isset($_GET["error"])){
             if ($_GET["error"] == "invalidProjectID") {
                 echo "<p>Zur angegebenen Projekt ID besteht kein Projekt!</p>";
             }
           elseif ($_GET["error"] == "noNegativeProjectID") {
                echo "<p>Die Projekt ID muss einen numerischen Wert enthalten!</p>"; 
            } 
            elseif ($_GET["error"] == "noAccess") {
                echo "<p>Die PNR des Projektleiters muss darf nur nummerische Werte enthalten!</p>";
            }
             elseif ($_GET["error"] == "none") {
                 echo "<p>Das Projekt wurde erfolgreich angelegt!</p>";
             }

         }

         ?>



</html>
