<?php

include_once '../../includes/dbh.inc.php';

if (isset($_POST['button_save_workingTime'])) {

    //$lastDateEnderedPlusOne = Last date auslesen, ein Tag addieren und ausgeben

    //$pnr = angemeldete PNR auslesen und ausgeben

    $projectID = $_POST['projectID'];
    $projectTaskID = $_POST['projectTaskID'];
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

    /*
    if(none(??) !== false){
        header("location: ../workingTimeRecording.php?error=noen");
        exit();
    }
    */
