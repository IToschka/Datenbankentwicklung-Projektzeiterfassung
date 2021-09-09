<?php

include_once '../../includes/dbh.inc.php';
include_once 'workingTimeRecordingFunctions.inc.php';
            

if (isset($_POST['button_save_workingTime'])) {
    
    //Arrays für Start und Endtime
    $beginTime = array();
    $endTime = array();

    $pnr = $_POST['pnr'];
    $recordingDate = $_POST['recordingDate'];
    $projectID = $_POST['projectID'];
    $projectTaskID = $_POST['projectTaskID'];
    

    //Array
    $beginTime = $_POST['beginTime'];
    $endTime = $_POST['endTime'];

    //Validierung für eine evtl. Fehlermeldung
    for ($i = 0; $i < $countResult; $i++){

        //Es wurde nur eine Beginzeit bei (min) einer Projektaufgabe eigetragen
        if(onlyBeginInput($beginTime, $endTime) !== false){
            header("location: ../workingTimeRecording.php?error=onlyBeginInput");
            exit();
        }

        //Es wurde nur eine Endzeit bei (min) einer Projektaufgabe eigetragen
        if(onlyEndInput($beginTime, $endTime) !== false){
            header("location: ../workingTimeRecording.php?error=onlyEndInput");
            exit();
        }
        
        //Die Endzeit liegt vor der Startzeit
        elseif(beginIsAfterEnd($beginTime, $endTime) !== false){
            header("location: ../workingTimeRecording.php?error=beginIsAfterEnd");
            exit();
        }

        /*//Es wurde gar keine Zeit eingetragen --> an keinem Projekt gearbeitet --> nur lastDateEntered hochsetzen
        elseif(bothTimesEmpty($beginTime, $endTime) !== false){
            header("location: ../workingTimeRecording.php?error=allTimesEmpty");
            updateLastDateEntered($conn, $recordingDate);
            exit();
        }*/

        //Es wurden alle Zeiten syntaktisch richtig eingetragen --> Projekt soll in Array gespeichert werden
        else{
            saveTimeRecoring($conn, $pnr, $projectID, $projectTaskID, $recordingDate, $beginTime, $endTime);
            updateLastDateEntered($conn, $recordingDate);
        }
    
    }
} 
