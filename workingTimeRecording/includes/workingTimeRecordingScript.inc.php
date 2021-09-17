<?php
//Autor der Datei Irena Toschka

//Das ist die Skript-Datei, welche vom Formular der Arbeitszeiterfassungsseite aufgerufen wird
//Zu Beginn werden die aus Formular übergebenen Einträge über ein POST-Befehl in das Skript geladen
//Anschließend werden Arrays für die Beginn- und die Endzeit erstellt
//Dann wird über alle die Projekteinträge iteriert, dazu werden die eingetragnene Zeiten aus der AHutseite geholt
//Anschließend wird Eintrag für Eintrag geprüft ob dieser in Ordnung ist
//Sobald ein Eintrag nicht konform ist, wird der Fehlercode der Hauptseite aufgerufen und der User muss die Daten neueingeben
//Bei einem leeren Eintrag wird die ProjektID sowie die ProjektTaskID aus den entsprechenden Arrays gelöscht
//Wenn alle Projekte ohne Fehler durchiteriert wurden, werden die Lücken aus beiden Arrays (von ProjektID und ProjektTaskID) rausgenommen
//Dies wird gemacht indem diese in ein neues Array gespeichert werden, ohne die Lücken dabei zu berücksichtigen
//Anschließend können die Eintröge gespeichert werden und das lastDateEndered geupdated werden
//Wenn alle Zeiten leer sin, also der User an dem Tag an keinem Projekt gearbeitet hat, wird lediglich das lastDateEndered geupdated  
//Ergänzung: Der ExitCode hat keine wesentliche Funktion, sondern dient der Übersichtlichkeit


session_start();
include_once '../../includes/dbh.inc.php';
include_once 'workingTimeRecordingFunctions.inc.php';
            

if (isset($_POST['button_save_workingTime'])) {
    
    $pnr = $_POST['pnr'];
    $recordingDate = $_POST['recordingDate'];
    $projectIDArray = $_SESSION['projectA'];
    $projectTaskIDArray = $_SESSION['projectTaskA'];
    $countResult = $_SESSION['countResult'];
        
    $beginTimeA = array();
    $endTimeA = array();

    //Validierung für eine evtl. Fehlermeldung bzw. ob Speicherung in Array
    for ($i = 0; $i < $countResult; $i++){

        $beginTime = $_POST["beginTime{$i}"];
        $endTime = $_POST["endTime{$i}"];
        
        $exitCode = 0;

        //Es wurde nur eine Beginzeit bei (min) einer Projektaufgabe eigetragen
        if(onlyBeginInput($beginTime, $endTime) == true){
            $exitCode = 1;
            header("location: ../workingTimeRecording.php?error=onlyBeginInput");
            break;
        }

        //Es wurde nur eine Endzeit bei (min) einer Projektaufgabe eigetragen
        elseif(onlyEndInput($beginTime, $endTime) == true){
            $exitCode = 1;
            header("location: ../workingTimeRecording.php?error=onlyEndInput");
            break;
        }
        
        //Die Endzeit liegt vor der Startzeit
        elseif(beginIsAfterEnd($beginTime, $endTime) == true){
            $exitCode = 1;
            header("location: ../workingTimeRecording.php?error=beginIsAfterEnd");
            break;
        }

        //Es liegt eine Überlappung bei (min) zwei Projekten vor --> hier mit Array arbeiten
        elseif(overlappingProjects($beginTime, $endTime, $beginTimeA, $endTimeA) == true){
            $exitCode = 1;
            header("location: ../workingTimeRecording.php?error=overlappingProjects");
            break;
        }

        //Für das Projekt wurde keine gar keine Zeit eingetragen --> kein Fehler, aber auch trotzdem keine Speicherung im Array
        elseif(bothTimesEmpty($beginTime, $endTime) == true){
            $exitCode = 0;
            $projectIDArray[$i] = null;
            $projectTaskIDArray[$i] = null;
        }

        //Kein Fehler --> Speicherung in Array
        else{    
            array_push($beginTimeA, $beginTime);
            array_push($endTimeA, $endTime);   
        }

    } //hier endet die for-Schleife


    //Lücken aus demm Projekt-Array und dem Projektaufgaben-Array raus, indem die Arrays neu in ein zweites Array gepeichert werden
    $projectIDArray2 = array();
    for($i = 0; $i < count($projectIDArray); $i++){
        if ($projectIDArray[$i] != null) {
            array_push($projectIDArray2, $projectIDArray[$i]);
        }
    }
    $projectTaskIDArray2 = array();
    for($i = 0; $i < count($projectTaskIDArray); $i++){
        if ($projectTaskIDArray[$i] != null) {
            array_push($projectTaskIDArray2, $projectTaskIDArray[$i]);
        }
    }



    //Es wurde gar keine Zeit eingetragen (Array leer) --> nur updateLastDateEntered
    if(emptyArray($beginTimeA, $endTimeA) == true && $exitCode == 0 ){
        echo "Test Empty Array";
        updateLastDateEntered($conn, $recordingDate, $pnr);
        if($recordingDate < $datum = date("Y-m-d", $timestamp)) {
            header("location: ../workingTimeRecording.php"); 
        }
        else{
            if(!isset($_SESSION['projectManager'])){
                header("location: ../../menu/employeeMenu.php");
            }            
            else{
                header("location: ../../menu/projectManagerMenu.php");
            }    
        }
    }
    //Zeiten aus Array abspeichern + update
    elseif($exitCode == 0){
        for($i=0; $i < count($projectIDArray2); $i++){
          saveTimeRecoring($conn, $pnr, $projectIDArray2[$i], $projectTaskIDArray2[$i], $recordingDate,
                           $beginTimeA[$i] = str_replace(':', '', $beginTimeA[$i])."00", $endTimeA[$i]= str_replace(':', '', $endTimeA[$i])."00");
        }
        updateLastDateEntered($conn, $recordingDate, $pnr);
        if($recordingDate < $datum = date("Y-m-d", $timestamp)) {
            header("location: ../workingTimeRecording.php"); 
        }
        else{
            if(!isset($_SESSION['projectManager'])){
                header("location: ../../menu/employeeMenu.php");
            }            
            else{
                header("location: ../../menu/projectManagerMenu.php");
            }    
        }
    }
    else{
        //do nothing, denn es wird bereits Fehler ausgegeben, wenn exitCode = 1
    }
} 
