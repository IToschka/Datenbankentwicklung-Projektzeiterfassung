<?php
//Funktion zur Bestimmung des Erfassungsdatum
function recordingDate($conn, $pnr) {
    //Abruf in DB
    $sql = "SELECT LastDateEntered FROM employee WHERE PNR = ?;";
    //Verbindung zur DB
    $stmt = mysqli_stmt_init($conn);
    //Statement wird vorbereitet
    if(!mysqli_stmt_prepare($stmt, $sql)){
       echo "Das ist ein Fehler!";
       header("location: ../workingTimeRecording.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_prepare($stmt, "s", $pnr);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $recordingDate;
    //Verwendet wird die PHP-Funktion string to time (strtotime)
    while($row = mysqli_fetch_assoc($result)){
        $lastDateEntered = $row['lastDateEntered'];
        //Ist lastDateEntered Freitag --> soll auf Montag gesprungen werden --> 3 Tage weiter (Sa & So kommen dadurch nie vor)
        if(recordingDate('l', strtotime($lastDateEntered)) == 'Friday') {
            $recordingDate = date('m.d.y', strtotime("+3 day", strtotime($row['lastDateEntered'])));
        }
        else {
            //Ist lastDateEntered Mo-Do --> soll auf nächsten Tag gesprungen werden --> 1 Tag weiter     
            $recordingDate = date('m.d.y', strtotime("+1 day", strtotime($row['lastDateEntered'])));
        }   
    }
    return $recordingDate;
    header("location: ../workingTimeRecording.php");
}

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

/*
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