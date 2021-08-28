<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <title>Arbeitszeiterfassung</title>
    </head>

    <body>
        <h1>Erfassungsbereich der Projektarbeitszeiten</h1>
            <form action="includes/workingTimeRecordingScript.inc.php" method="POST">
                <table>
                    <tbody>
                        <tr> 
                            <td>Erfassungsdatum:</td>
                            <td><input type="date" name="recordingDate" placeholder="Datum">
                        </tr>
                        <tr>
                            <td>Personalnummer:</td>
                            <td><input type="text" name="pnr" 
                                value=<?php
                                    session_start();
                                    $pnr = $_SESSION['pnr'];
                                    echo $pnr;
                                    ?>>
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
                    <tbody>
                        <tr>
                            <td><input type="text" name="projectID" placeholder="Projekt"></td>
                            <td><input type="text" name="projectTaskID" placeholder="Projektaufgabe"></td>
                            <td><input type="time" name="beginTime" placeholder="Von"></td>
                            <td><input type="time" name="endTime" placeholder="Bis"></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <input type="submit" name="button_save_workingTime" value="Speichern">

            </form>

            <?php

              include_once '../includes/functions.inc.php';

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
