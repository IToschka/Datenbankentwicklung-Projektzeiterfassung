<?php
//Funktion zur Bestimmung des Erfassungsdatum
function recordingDate($conn) {
    //Abruf in DB
    $sql = "SELECT LastDateEntered FROM employee WHERE PNR = ?;";
    //Verbindung zur DB
    $stmt = mysqli_stmt_init($conn);
    //Statement wird vorbereitet
    //Statement funktioniert nicht
    if(!mysqli_stmt_prepare($stmt, $sql)){
       header("location: ../workingTimeRecording.php?error=stmtfailed");
        exit();
    }
    //Statement funktioniert
    else {
        //Parameter binden
        mysqli_stmt_bind_param($stmt, "s",  $_SESSION['pnr']);
        //Paramter in DB ausführen
        mysqli_stmt_execute($stmt);
        //Ergebnis abspeichern
        $result = mysqli_stmt_get_result($stmt);
    }
    $recordingDate;
    //Verwendet wird die PHP-Funktion string to time (strtotime)
    while($row = mysqli_fetch_assoc($result)){
        $lastDateEntered = $row['LastDateEntered'];
        //Ist lastDateEntered Freitag --> soll auf Montag gesprungen werden --> 3 Tage weiter (Sa & So kommen dadurch nie vor)
        if(date('l', strtotime($lastDateEntered)) == 'Friday') {
            $recordingDate = date('Y-m-d', strtotime("+3 day", strtotime($row['LastDateEntered'])));
        }
        else {
            //Ist lastDateEntered Mo-Do --> soll auf nächsten Tag gesprungen werden --> 1 Tag weiter     
            $recordingDate = date('Y-m-d', strtotime("+1 day", strtotime($row['LastDateEntered'])));
        }
        return $recordingDate;
     }
}

//Funktionien für die Validierung für eine evtl. Fehlermeldung
function onlyBeginInput($beginTime, $endTime){
    $result;
    if($beginTime != null && $endTime == null){
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}

function onlyEndInput($beginTime, $endTime){
    $result;
    if($beginTime == null && $endTime != null) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function beginIsAfterEnd($beginTime, $endTime){
    if($beginTime > $endTime) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function overlappingProjects($beginTime, $endTime, $beginTimeA, $endTimeA){
    for($i = 0; $i < count($beginTimeA); $i++) {
        if($beginTime > $beginTimeA[$i] && $beginTime < $endTimeA[$i]) {
            return true;
        }
        elseif($endTime < $endTimeA[$i] && $endTime > $beginTimeA[$i]){
            return true;  
        }

    }
    
    return false;
}

function bothTimesEmpty($beginTime, $endTime){
    if($beginTime == null && $endTime == null) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
    return $countEmptyInput;
}

function emptyArray($beginTimeA, $endTimeA){
    if(count($beginTimeA) == 0) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}


//Funktion zum Speichern der Einträge
function saveTimeRecoring($conn, $pnr, $projectID, $projectTaskID, $recordingDate, $beginTime, $endTime){
    //Manipulation in DB
    $sql = "INSERT INTO timeRecording (PNR, ProjectID, ProjectTaskID, RecordingDate, TaskBegin, TaskEnd) VALUES (?, ?, ?, ?, ?, ?);";
    //Verbindung zu DB
    $stmt = mysqli_stmt_init($conn);
    //Statement wird vorbereitet
    if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../workingTimeRecording.php?error=stmtfailed");
      exit();
    }else{
        //Parameter binden
        mysqli_stmt_bind_param($stmt, "ssssss", $pnr, $projectID, $projectTaskID, $recordingDate, $beginTime, $endTime);
        //Parameter in DB ausführen
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

 

}

//Funktion zum Ändern des LastDateEntered
//Diese Funktion darf nur aufgerufen werden, wenn alles richtig (ErrorCode=0) ist oder nichts eingetragen wurde
function updateLastDateEntered($conn, $recordingDate, $pnr){
    //Manipulation in DB
    $sql = "UPDATE employee SET LastDateEntered = ? WHERE PNR = ?;";
    //Verbindung zu DB
    $stmt = mysqli_stmt_init($conn);
    //Statement wird vorbereitet
    if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../workingTimeRecording.php?error=stmtfailed");
      exit();
    }else{
        //Parameter binden
        mysqli_stmt_bind_param($stmt, "ss",$recordingDate,  $_SESSION['pnr']);
        //Parameter in DB ausführen
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt); 
    }
}