<?php
    session_start();
    include_once '../menu/projectsAndTasksMenu.php';

    
?>
<!DOCTYPE html>
<html>

    <head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="../css/style.css">
   <title>Projekt kopieren</title>
    </head>

    <body>
        <form action="includes/projectAndTasksScript.inc.php" method="POST" >

<?php include_once '../includes/dbh.inc.php';
        
$sql = "SELECT ProjectName, BeginDate, ProjectManagerPNR FROM project WHERE ProjectID = '1';"; //pnr als Sessionvariable
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);

        if($resultCheck > 0) {
                while($row = mysqli_fetch_assoc($result)) { ?>
                        
        
          
        <table>
                  <tbody>
                      <tr>
                          <td>Projekttitel:</td>
                          <td><textarea name="projectName"  maxlength="50" cols="50"> <?php echo $row["ProjectName"]; ?></textarea></td>
                      </tr>
                      <tr>
                          <td>Starttermin:</td>
                          <td><input type="date" name="beginDate"><?php $date = $row["BeginDate"]; $timestamp = strtotime($date); $newDate = date("d.m.Y", $timestamp); echo $newDate; ?></td> <!-- als Date konvertieren -->
                      </tr>
                      <tr>
                      <td>PNR Projektleiter:</td>
                      <td><input type="text" texarea readonly ="readonly" name="projectManager" value= <?php $projectManager = $_SESSION['pnr']; echo $projectManager ?> ><?php }}?></td> <!-- als Sessionvariable -->
                      <tr>
                          <td>Anzahl der Aufgaben</td>
                          <td><input type="number" name="amountTasks"></td>
                      </tr>
                  </tbody>
              </table>

            <input type="submit" name="button_copy" value="Eingaben speichern">
            <input type="submit" name="button_projectManagerMenu" value="Zurück zum Hauptmenü">
            
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
                echo "<p>Die PNR des Projektleiters muss darf nur nummerische Werte enthalten!</p>";
            }
             elseif ($_GET["error"] == "none") {
                 echo "<p>Das Projekt wurde erfolgreich angelegt!</p>";
             }

         }

         ?>



    </body>

</html>
