<?php

include_once '../../includes/dbh.inc.php';
include_once '../../includes/loginHeader.php';

if (isset($_POST['button_save_workingTime'])) {

    $recordingDate;
    // = Last date auslesen, ein Tag addieren und ausgeben, wenn Wochentag --> function

    $pnr;
    $projectID;
    $projectTaskID;
    $beginTime = $_POST['beginTime'];
    $endTime = $_POST['endTime'];

    require_once 'workingTimeRecordingFunctions.inc.php';

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
        

    /*
    if(none(??) !== false){
        header("location: ../workingTimeRecording.php?error=noen");
        exit();
    }
    */
