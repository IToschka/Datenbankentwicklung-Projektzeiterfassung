<?php

include_once '../../includes/dbh.inc.php';
include_once 'workingTimeRecordingFunctions.inc.php';
            
//Abrufen der Funktion, mit welcher das Erfassungsdatum bestimmt wird
//$recordingDate = recordingDate($conn, $pnr);

if (isset($_POST['button_save_workingTime'])) {

    $recordingDate = $_POST['recordingDate'];
    // = Last date auslesen, ein Tag addieren und ausgeben, wenn Wochentag --> function

    $pnr = $_POST['pnr'];
    $projectID = $_POST['projectID'];
    $projectTaskID = $_POST['projectTaskID'];
    $beginTime = $_POST['beginTime'];
    $endTime = $_POST['endTime'];

    if(invalidTime($beginTime, $endTime) !== false){
        header("location: ../workingTimeRecording.php?error=invalidTime");
        exit();
    }

    /*
    if(stmtfailed(??) !== false){
        header("location: ../workingTimeRecording.php?error=stmtfailed");
        exit();
    }
    */

    if(emptyInput($beginTime, $endTime) !== false){
        header("location: ../workingTimeRecording.php?error=emptyInput");
        //lastDateEntered bei der PNR in Personaltabelle auf $lastDateEnderedPlusOne setzen
        exit();
    }

    if(oneEmptyInput($beginTime, $endTime) !== false){
        header("location: ../workingTimeRecording.php?error=oneEmptyInput");
        exit();
    }

    saveTimeRecoring($conn, $pnr, $projectID, $projectTaskID, $recordingDate, $beginTime, $endTime);
} 
