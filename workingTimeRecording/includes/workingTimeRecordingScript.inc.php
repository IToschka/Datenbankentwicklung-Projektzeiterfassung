<?php

include_once '../../includes/dbh.inc.php';
include_once 'workingTimeRecordingFunctions.inc.php';
            

if (isset($_POST['button_save_workingTime'])) {
  
    $pnr = $_POST['pnr'];
    $recordingDate = $_POST['recordingDate'];
    $projectID = $_POST['projectID'];
    $projectTaskID = $_POST['projectTaskID'];
    $beginTime = $_POST['beginTime'];
    $endTime = $_POST['endTime'];

    
    /*
    if(invalidTime($beginTime, $endTime) !== false){
        header("location: ../workingTimeRecording.php?error=invalidTime");
        exit();
    }

    if(emptyInput($beginTime, $endTime) !== false){
        header("location: ../workingTimeRecording.php?error=emptyInput");
        //lastDateEntered bei der PNR in Personaltabelle auf $lastDateEnderedPlusOne setzen
        exit();
    }

    if(oneEmptyInput($beginTime, $endTime) !== false){
        header("location: ../workingTimeRecording.php?error=oneEmptyInput");
        exit();
    }*/

    saveTimeRecoring($conn, $pnr, $projectID, $projectTaskID, $recordingDate, $beginTime, $endTime);
} 
