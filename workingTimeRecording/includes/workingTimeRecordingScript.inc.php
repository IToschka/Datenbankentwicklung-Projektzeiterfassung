<?php

session_start();
include_once '../../includes/dbh.inc.php';
include_once 'workingTimeRecordingFunctions.inc.php';
            

if (isset($_POST['button_save_workingTime'])) {
    
    $pnr = $_POST['pnr'];
    $recordingDate = $_POST['recordingDate'];
    $projectIDArray = $_SESSION['projectP'];
    $projectTaskIDArray = $_SESSION['projectTaskPT'];
    $countResult = $_SESSION['countResult'];


    //Validierung für eine evtl. Fehlermeldung
    for ($i = 0; $i < $countResult; $i++){

        $beginTime = $_POST["beginTime{$i}"];
        $endTime = $_POST["endTime{$i}"];

        //Es wurde nur eine Beginzeit bei (min) einer Projektaufgabe eigetragen
        if(onlyBeginInput($beginTime, $endTime) !== false){
            header("location: ../workingTimeRecording.php?error=onlyBeginInput");
            break;
        }

        //Es wurde nur eine Endzeit bei (min) einer Projektaufgabe eigetragen
        if(onlyEndInput($beginTime, $endTime) !== false){
            header("location: ../workingTimeRecording.php?error=onlyEndInput");
            break;
        }
        
        //Die Endzeit liegt vor der Startzeit
        elseif(beginIsAfterEnd($beginTime, $endTime) !== false){
            header("location: ../workingTimeRecording.php?error=beginIsAfterEnd");
            break;
        }

        /*//Es wurde gar keine Zeit eingetragen --> an keinem Projekt gearbeitet --> nur lastDateEntered hochsetzen
        elseif(bothTimesEmpty($beginTime, $endTime) !== false){
            header("location: ../workingTimeRecording.php?error=allTimesEmpty");
            updateLastDateEntered($conn, $recordingDate);
            header("location: ../workingTimeRecording.php");
            break;
        }*/

        //Überlappende Zeiten noch berücksichtigen

        //Es wurden alle Zeiten syntaktisch richtig eingetragen --> Projekt soll in Array gespeichert werden
        else{
            saveTimeRecoring($conn, $pnr, $projectIDArray[$i], $projectTaskIDArray[$i], $recordingDate, $beginTime, $endTime);
            updateLastDateEntered($conn, $recordingDate, $pnr);
            header("location: ../workingTimeRecording.php");
        }
    
    }
} 
