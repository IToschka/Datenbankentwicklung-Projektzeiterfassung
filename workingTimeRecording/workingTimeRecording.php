<?php
//Autor der Datei Irena Toschka

    include_once '../includes/loginHeader.inc.php';
    include_once '../includes/dbh.inc.php';
?>
<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <link rel="stylesheet" href="../css/style.css">
       <title>Arbeitszeiterfassung</title>
    </head>

    <body>
        <h1>Erfassungsbereich der Projektarbeitszeiten</h1>
            <form action="includes/workingTimeRecordingScript.inc.php" method="POST">
                    <br>
                    <table>
                        <tbody>
                            <tr>
                                <td>Personalnummer:</td>
                                 <td><input type="text" textarea readonly="readonly" name="pnr"
                                        value= '<?php $pnr = $_SESSION['pnr']; echo $pnr; ?>'>
                                </td>
                            </tr>
                            <tr> 
                                <td>Erfassungsdatum:</td>
                                <td><input type="date" textarea readonly="readonly" name="recordingDate"
                                        value= '<?php 
                                        require_once "includes/workingTimeRecordingFunctions.inc.php";
                                        $recordingDate = recordingDate($conn); echo $recordingDate; ?>'>
                                </td>
                            </tr>
                        </tbody>    
                    </table> 
                    <br>  

                <?php 
                    //Erstellen von diversen Hilsvariablen, die später benötigt werden
                    //Anzahl der Ergebnisse der SQL-Abfrage
                    $countResult;

                    //Variable für die Anzahl der Projekte, die dem Mitarbeiter angezeigt werden
                    $countRow = 0;

                    //Liste für Projkte und Projektaufgaben sowie für die Begin- und die Endzeit
                    $projectA = array();
                    $projectTaskA = array();                                       
                    
                    //Projekt und Projektaufgeben, abhängig von PNR, dem Erfassungsdatum und dem Projektstart ausgeben
                    //Abruf in DB
                    $sql ='SELECT p.ProjectID, pt.ProjectTaskID, p.ProjectName, pt.TaskDescription
                        FROM employeeproject ep, project p, projecttask pt
                        WHERE ep.PNR = ? AND ep.ProjectID = p.ProjectID AND p.ProjectID = pt.ProjectID AND ? >= p.BeginDate;';
                    //Verbindung zu DB
                    $stmt = mysqli_stmt_init($conn);
                    //Statement wird vorbereitet
                    //Statement funktioniert nicht
                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        header("location: workingTimerRecording.php?error=stmtfailed");
                        exit();
                    }
                    //Statement funktioniert nicht
                    else{
                        //Parameter binden
                        mysqli_stmt_bind_param($stmt, "ss", $pnr, $recordingDate);
                        //Paramter in DB ausführen
                        mysqli_stmt_execute($stmt);
                        //Ergebnis abspeichern
                        $result = mysqli_stmt_get_result($stmt);
                    }

                    //Anzahl der Projektaufgaben in die dafür erstelle Variable speichern
                    $countResult = mysqli_num_rows($result);
                    $_SESSION['countResult'] = $countResult;
                    
                    
                ?> 
                    <table>
                            <thead>
                                <tr>
                                    <td>ProjektID:</td>
                                    <td>ProjektaufgabenID:</td>
                                    <td>Beginn:</td>
                                    <td>Ende:</td>
                                </tr>
                            </thead>    
                        <?php
                            
                            //Zeilen erstellen und mit Projekten füllen
                            while($row = mysqli_fetch_assoc($result)) {
                            echo '<tbody>
                                        <tr>
                                            <td><input type="text" textarea readonly="readonly" style="width:200px; text-overflow:ellipsis;" name="projectID"
                                                value=' .$row['ProjectName'] = str_replace(' ', '_', $row['ProjectName']).'></td>
                                            <td><input type="text" textarea readonly="readonly" style="width:300px; text-overflow:ellipsis;" name="projectTaskID"
                                                value=' .$row['TaskDescription'] = str_replace(' ', '_', $row['TaskDescription']).'></td>
                                            <td><input type="time" name="beginTime'.$countRow.'"></td>
                                            <td><input type="time" name="endTime'.$countRow.'"></td>
                                        </tr>
                                </tbody>';

                                //Einträge in die Array-Listen (projectP und projectTaskPT) speichern
                                array_push($projectA, $row['ProjectID']);
                                array_push($projectTaskA, $row['ProjectTaskID']);

                                //Zählervariable nach jeder Tabellenzeile um ein erhöhen
                                $countRow++;                                  
                            }
                            
                            //Projekte und Projektaufgaben in Array
                            $_SESSION['projectA'] = $projectA;
                            $_SESSION['projectTaskA'] = $projectTaskA;

                        ?>                  
                    </table>
                    <br>
                <input type="submit" name="button_save_workingTime" value="Speichern">

            </form>
            <center> 
            

            <!--Buttons für zurück ins Menu und Abmeldung-->
            <form action="../includes/footer.inc.php" method="POST" >
            <input type="submit" name="button_BackToMenu" value="Zurück zum Menü">
            <input type="submit" name="button_LogOut" value = "Abmelden">
            </form>

            <?php
            include_once 'includes/workingTimeRecordingFunctions.inc.php';
            //Abfangen der einzelnen Fehlermeldungen 
            if(isset($_GET["error"])){
                if($_GET["error"] == "onlyBeginInput"){
                echo "Bei mindestens einer Projektzeit fehlt die Endzeit.";
                }
                elseif($_GET["error"] == "onlyEndInput"){
                    echo "Bei mindestens einer Projektzeit fehlt die Beginnzeit.";
                    }                  
                elseif($_GET["error"] == "beginIsAfterEnd"){
                    echo "Die Beginnzeit muss vor der Endzeit liegen!";
                } 
                elseif($_GET["error"] == "overlappingProjects"){
                    echo "Bei mindestens zwei Projekten haben sich die eigetragenen Zeiten überlappt.";
                }
                elseif($_GET["error"] == "stmtfailed"){
                    echo "Etwas ist schief gelaufen!";
                }
                elseif($_GET["error"] == "none"){
                    echo "Die Projektzeit(en) wurde(n) erfolgreich erfasst.";

                }
            }
            ?>
            </center>
    </body>
</html>
