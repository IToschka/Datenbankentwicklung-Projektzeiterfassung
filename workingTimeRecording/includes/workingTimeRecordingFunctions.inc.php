<?php
//Autor der Datei Irena Toschka

//Das ist die Datei, in welcher alle Funktionen ausgelagert wurden
//Als erstes gibt es darin die Funktion das Generieren des Erfassungsdatums aus dem lastDateEntered
//Anschließend folgen alle Funktionen für die Validierung der Zeiteneingabe (z.B. Überlappungen, Endzeit vor Beginnzeit, ...)
//Ganz zum Schluss gibt es dann noch die zwei Funktionen für die Speicherung in der DB
//Das ist zum einen die Speicherung der Projektarebitszeiten (falls Zeiten eingetragen wurden)
//und zum andere die Speicherung bzw. das Updaten des lastDateEntered


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
//Wenn nur eine Beginnzeit eingetragen wurde
function onlyBeginInput($beginTime, $endTime){
    if($beginTime != null && $endTime == null){
        return true;
    }
    else{
        return false;
    }
}

//Wenn nur eine Enzeit eingetragen wurde
function onlyEndInput($beginTime, $endTime){
    if($beginTime == null && $endTime != null) {
        return true;
    }
    else {
        return false;
    }
}

//Wenn die Endzeit vor der Beginnzeit liegt
function beginIsAfterEnd($beginTime, $endTime){
    if($beginTime > $endTime) {
        return true;
    }
    else {
        return false;
    }
}

//Wenn sich min. zwei Prjekte überlappen
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

//Wenn bei einem Projekt nichts eingetragen wurde
function bothTimesEmpty($beginTime, $endTime){
    if($beginTime == null && $endTime == null) {
        return true;
    }
    else {
        return false;
    }
}
//Wenn gar nichts eingetragen wurde
function emptyArray($beginTimeA, $endTimeA){
    if(count($beginTimeA) == 0) {
        return true;
    }
    else {
        return false;
    }
}


//Funktion zum Speichern der Einträge
//Diese Funktion darf nur aufgerufen werden, wenn alles richtig ist eingetragen wurde
function saveTimeRecoring($conn, $pnr, $projectID, $projectTaskID, $recordingDate, $beginTime, $endTime){
    //Manipulation in DB
    $sql = "INSERT INTO timeRecording (PNR, ProjectID, ProjectTaskID, RecordingDate, TaskBegin, TaskEnd) VALUES (?, ?, ?, ?, ?, ?);";
    //Verbindung zu DB
    $stmt = mysqli_stmt_init($conn);
    //Statement wird vorbereitet
    if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../workingTimeRecording.php?error=stmtfailed");
      exit();
    }
    //Statement funktioniert 
    else{
        //Parameter binden
        mysqli_stmt_bind_param($stmt, "ssssss", $pnr, $projectID, $projectTaskID, $recordingDate, $beginTime, $endTime);
        //Parameter in DB ausführen
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

//Funktion zum Ändern des LastDateEntered
//Diese Funktion darf nur aufgerufen werden, wenn alles richtig ist oder nichts eingetragen wurde
function updateLastDateEntered($conn, $recordingDate, $pnr){
    //Manipulation in DB
    $sql = "UPDATE employee SET LastDateEntered = ? WHERE PNR = ?;";
    //Verbindung zu DB
    $stmt = mysqli_stmt_init($conn);
    //Statement wird vorbereitet
    //Statement funktioniert nicht
    if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../workingTimeRecording.php?error=stmtfailed");
      exit();
    }
    //Statement funktioniert
    else{
        //Parameter binden
        mysqli_stmt_bind_param($stmt, "ss",$recordingDate,  $_SESSION['pnr']);
        //Parameter in DB ausführen
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt); 
    }
}