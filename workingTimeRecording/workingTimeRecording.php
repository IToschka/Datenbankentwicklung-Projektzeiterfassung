<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <title>Arbeitszeiterfassung</title>
    </head>

    <body>
        <?php
            session_start();
            include_once '../includes/dbh.inc.php';


  
        ?>
        <h1>Erfassungsbereich der Projektarbeitszeiten</h1>
            <form action="includes/workingTimeRecordingScript.inc.php" method="POST">
                <?php /*
                    //Projekt und Projektaufgeben, abhängig von PNR, dem Erfassungsdatum und dem Projektstart ausgeben
                    $sql ='SELECT ProjectID, ProjectTaskID, ProjectTask FROM employeeproject ep, project p, projecttask pt
                        WHERE ep.pnr = ? AND ep.projectID = p.projectID AND p.projectID = pt.projectID AND ? >= p.BeginDate;';
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        header("location: workingTimerRecording.php?error=stmtfailed");
                        exit();
                    }
                    $result->get_result();

                    //Liste für Projkte und Projektaufgaben
                    $projectP = array();
                    $projectTaskPT = array();

                    //Anzahl der Ergebnisse der SQL-Abfrage
                    $countResult = $result->num_rows();

                    //Variable für die Anzahl der Projekte, die dem Mitarbeiter angezeigt werden
                    $countRow = 0;*/
                ?>
                    <table>
                        <tbody>
                            <tr> 
                                <td>Erfassungsdatum:</td>
                                <td><?php 
                                require_once "includes/workingTimeRecordingScript.inc.php";
                                echo $recordingDate;
                                ?>
                            </tr>
                            <tr>
                                <td>Personalnummer:</td>
                                <td><input type="text" textarea readonly="readonly" name="pnr"
                                        value= <?php $pnr = $_SESSION['pnr']; echo $pnr ?>></td>
                                </td>
                            </tr>
                    </table> 
                    <br>   
                    <table>
                            <thead>
                                <tr>
                                    <td>ProjektID:</td>
                                    <td>ProjektaufgabenID:</td>
                                    <td>Beginn:</td>
                                    <td>Ende:</td>
                                </tr>
               <?php /*
                   //Zeilen erstellen und mit Projekten füllen
                    while($countResult = $result->fetch_assoc()) {
                        echo                 
                        '<tbody>
                                <tr>
                                    <td><input type="text" name="projectID" value=  $countResult['projectID']></td>
                                    <td><input type="text" name="projectTaskID" value=  $countResult['projectTaskID']></td>
                                    <td><input type="time" name="beginTime'.$countRow'"></td>
                                    <td><input type="time" name="endTime'.$countRow'"></td>
                                </tr>
                            </tbody>';
                        
                    }
                    </table>*/
                ?>
                <br>
                <input type="submit" name="button_save_workingTime" value="Speichern">

            </form>

            <?php

              include_once 'includes/workingTimeRecordingFunctions.inc.php';

            if(isset($_GET["error"])){
                if($_GET["error"] == "invalidTime"){
                echo "<p>Die Uhrzeit bei Beendigung darf nicht vor der Uhrzeit bei Beginn liegen!";
                }
                elseif($_GET["error"] == "stmtfailed"){
                    echo "<p>Etwas ist schief gelaufen!</p>";
                }
                elseif($_GET["error"] == "emptyInput"){
                echo "<p>Es wurde erfasst, das der Mitarbeiter an diesem Tag an keinem Projekt gearbeitet hat.</p>";
                }
                elseif($_GET["error"] == "oneEmptyInput"){
                    echo "<p>Es müssen beide Zeiten eingetragen werden, Uhrzeit bei Beginn UND Uhrzeit bei Beendigung.</p>";
                    }
                elseif($_GET["error"] == "none"){
                    echo "<p>Die Projektzeit(en) wurde(n) erfolgreich erfasst.</p>";
                }
            }
            ?>

    </body>
</html>
