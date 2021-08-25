<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <title>Arbeitszeiterfassung</title>
    </head>

    <body>
        <h1>Erfassungsbereich der Projektarbeitszeiten</h1>
            <form action="includes/workingTimeRecording.inc.php" method="POST">
                <input type="date" name="lastDateEnderedPlusOne" placeholder="Datum">
                <input type="text" name="yourPNR" placeholder="PNR">
                <table>
                    <tbody>
                        <tr>
                            <td>ProjektID:</td>
                            <td><input type="text" name="projectID" placeholder="Projekt"></td>
                        </tr>
                        <tr>
                            <td>Personalnummer:</td>
                            <td><input type="text" name="projectTaskID" placeholder="Projektaufgabe"></td>
                        </tr>
                        <tr>
                            <td>Uhrzeit bei Beginn:</td>
                            <td><input type="time" name="beginTime" placeholder="Von"></td>
                        </tr>
                        <tr>
                            <td>Uhrzeit bei Beendigung:</td>
                            <td><input type="time" name="endTime" placeholder="Bis"></td>
                        </tr>
                    </tbody>
                </table>
                    
                <input type="submit" name="save_workingTime" value="Speichern">
                
            </form>


            <?php
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