<?php
//Autor der Datei Tamara Romer und Irena Toschka

    include_once '../includes/loginHeader.inc.php';
    include_once '../includes/projectRoleHeader.inc.php';
    include_once '../includes/dbh.inc.php';
    include_once '../workingTimeRecording/includes/workingTimeRecordingFunctions.inc.php';
?>
<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <link rel="stylesheet" href="../css/style.css">
       <title>Projektmanager - Menü</title>
    </head>

    
    <body>
      <?php
        $recordingDate = recordingDate($conn);
      ?>
        <center>
        <h1>Projektmanager - Menü</h1>
        <table class="startMenuTable">
          <tr class="startMenuColumn">
           <td><a href="employeeManagementMenu.php">Erfassungsbereich der Mitarbeiter</a></td>
          </tr>
          <tr class ="startMenuColumn">
            <td><a href="projectsAndTasksMenu.php">Projekte & Projektaufgaben erfassen</a></td>
          </tr>
          <tr class="startMenuColumn">
           <td ><a href="../projectManagement/employeesAndProjects.php">Erfassungsbereich der Projektmitarbeiter</a></td>
          </tr>
          <tr class="startMenuColumn">
           <td > 
             <?php
                if($recordingDate <= $datum = date("Y-m-d")){
                  ?><a href="../workingTimeRecording/workingTimeRecording.php">Erfassungsbereich der Projektarbeitszeiten</a><?php
                }
                else{
                  ?><a href="projectManagerMenu.php">Die Projektzeiten wurden für den heutigen Tag schon erfasst!</a><?php
                }
              ?>             
            </td>
          </tr>
          <tr class="startMenuColumn">
           <td > <a href="workingTimeEvaluationMenu.php">Auswertungsbereich der Arbeitszeiten</a></td>
          </tr>
        </table>

          <!--Button Abmeldung-->
          <br>
          <form action="../includes/footer.inc.php" method="POST" >
          <input type="submit" name="button_LogOut" value = "Abmelden">
          </form>

        </center>

          

    </body>
</html>
