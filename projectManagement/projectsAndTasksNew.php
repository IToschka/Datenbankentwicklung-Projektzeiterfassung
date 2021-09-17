<?php
    //Autor: Katja Frei
    include_once '../menu/projectsAndTasksMenu.php';
    include_once '../includes/dbh.inc.php';
    include_once 'includes/projectManagementFunctions.inc.php';
?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="../css/style.css">
   <title>Neues Projekt anlegen </title>
</head>

    <body>
        <?php 
           $error = "";

            if(isset($_POST['button_createProject'])) {

                $projectName = $_POST['projectName'];
                $beginDate = $_POST['beginDate'];
                $amountTasks= $_POST['amountTasks'];
                $projectManager = $_POST['projectManager'];
              
                $date = date("d.m.Y");
                $dateTimestamp = strtotime($date);
                $beginDateTimestamp = strtotime($beginDate);


                           
              $error = invalidDate($dateTimestamp, $beginDateTimestamp);
              
                if ($error == "") {
                    $error = titleAlreadyExists($conn, $projectName);
                }
                // Projekt speichern
                if ($error == "") {
                    $error = createProject($conn, $projectName, $beginDate, $projectManager);
              
                }
                $_SESSION['projectID'] = getProjectID($conn);
                
            }
        ?>
        <!-- ruft sich selbst auf -->
        <form action="projectsAndTasksNew.php" method="POST" > 
          <table>
                  <tbody>
                      <tr>
                          <td>Projekttitel:</td>
                          <td><textarea name="projectName"  maxlength="50" cols="50" required></textarea></td>
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
                          <td><input type="number" name="amountTasks" min="1"></td>
                      </tr>
                  </tbody>
              </table>

            <input type="submit" name="button_createProject" value="Eingaben speichern">
            
            
         </form>
         

         <br>
         
         <form action="includes/projectAndTasksScript.inc.php" method="POST" > <table> <tbody>
               <?php 
               //Übernimmt die amountTasks und gibt so viele Aufgabenfelder aus wie angegeben
               if(isset($_POST['button_createProject'])) {
                $amountTasks = $_POST['amountTasks'];
               $_SESSION['amountTasks'] = $amountTasks;

               for($i=0; $i < $amountTasks; $i++) { ?>          
                <tr> <td><?php
                $i2 = $i + 1;
                echo "Aufgabe $i2:"; ?></td>
                    <?php
                    echo '<td> <textarea name="task'.$i.'" maxlength="2000" cols="50" required></textarea></td>';
                    ?>
                </tr>
                

       <?php } } ?>
        </tbody> </table> <input type='submit' name='button_createTasks' value='Aufgaben anlegen'> </form>
    <body>

         <?php

            if ($error == "invalidDate") {
                echo "<p>Das angegebene Datum liegt in der Vergangenheit!</p>";
            } 
            elseif ($error == "titleAlreadyExists") {
                echo "<p>Ein Projekt mit diesem Titel existiert bereits!</p>";
            }
            elseif ($error == "none") {
                echo "<p>Das Projekt wurde erfolgreich angelegt!</p>";
            }
        

         ?>



    </body>

</html>
