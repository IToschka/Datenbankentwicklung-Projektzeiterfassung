<?php

function invalidTime($beginTime, $endTime){
    $result;
    if($beginTime > $endTime){
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}

function emptyInput($beginTime, $endTime){
    $result;
    if(empty($beginTime) && empty($endTime)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function oneEmptyInput($beginTime, $endTime){
    if(empty($beginTime) || empty($endTime) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}


function saveTimeRecoring($conn, $pnr, $projectID, $projectTaskID, $recordingDate, $beginTime, $endTime){
  $sql = "INSERT INTO timerecording (PNR, ProjectID, ProjectTaskID, RecordingDate, TaskBegin, TaskEnd) VALUES (?, ?, ?, ?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
    ///Header muss angepasst
      header("location: ../createEmployee.php?error=stmtfailed");
      exit();
  }

  mysqli_stmt_bind_param($stmt, "ssssss",$pnr, $projectID, $projectTaskID, $recordingDate, $beginTime, $endTime);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

}
