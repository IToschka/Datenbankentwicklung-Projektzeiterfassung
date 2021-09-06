<?php
//Funktion zur Bestimmung des Erfassungsdatum
function recordingDate($conn, $pnr) {
    //Abruf in DB
    $sql = "SELECT LastDateEntered FROM employee WHERE PNR = ?;";
    //Verbindung zur DB
    $stmt = mysqli_stmt_init($conn);
    //Statement wird vorbereitet
    if(!mysqli_stmt_prepare($stmt, $sql)){
       header("location: ../workingTimeRecording.php?error=stmtfailed");
        exit();
    }else {
        //Parameter binden
        mysqli_stmt_bind_param($stmt, "s", $pnr);
        //Paramter in DB ausführen
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    }
    $recordingDate;
    //Verwendet wird die PHP-Funktion string to time (strtotime)
    while($row = mysqli_fetch_assoc($result)){
        $lastDateEntered = $row['LastDateEntered'];
        //Ist lastDateEntered Freitag --> soll auf Montag gesprungen werden --> 3 Tage weiter (Sa & So kommen dadurch nie vor)
        if(date('l', strtotime($lastDateEntered)) == 'Friday') {
            $recordingDate = date('d.m.y', strtotime("+3 day", strtotime($row['LastDateEntered'])));
        }
        else {
            //Ist lastDateEntered Mo-Do --> soll auf nächsten Tag gesprungen werden --> 1 Tag weiter     
            $recordingDate = date('d.m.y', strtotime("+1 day", strtotime($row['LastDateEntered'])));
        }
        return $recordingDate;
 
    }

}
/*
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
    if(empty($beginTime) || empty($endTime)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function saveTimeRecoring($conn, $pnr, $projectID, $projectTaskID, $recordingDate, $beginTime, $endTime){
  $sql = "INSERT INTO timeRecording (PNR, ProjectID, ProjectTaskID, RecordingDate, TaskBegin, TaskEnd) VALUES (?, ?, ?, ?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../workingTimeRecording.php?error=stmtfailed");
      exit();
  }

  mysqli_stmt_bind_param($stmt, "ssssss",$pnr, $projectID, $projectTaskID, $recordingDate, $beginTime, $endTime);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

}
*/