<!--Von Irena Toschka-->
<?php

session_start();
include_once '../../includes/dbh.inc.php';
include_once 'workingTimeRecordingFunctions.inc.php';
            

if (isset($_POST['button_save_workingTime'])) {
    
    $pnr = $_POST['pnr'];
    $recordingDate = $_POST['recordingDate'];
    $projectIDArray = $_SESSION['projectA'];
    $projectTaskIDArray = $_SESSION['projectTaskA'];
    $countResult = $_SESSION['countResult'];
        
    $beginTimeA = array();
    $endTimeA = array();

    //Validierung für eine evtl. Fehlermeldung bzw. ob Speicherung in Array
    for ($i = 0; $i < $countResult; $i++){

        $beginTime = $_POST["beginTime{$i}"];
        $endTime = $_POST["endTime{$i}"];
        
        $exitCode = 0;

        //Es wurde nur eine Beginzeit bei (min) einer Projektaufgabe eigetragen
        if(onlyBeginInput($beginTime, $endTime) == true){
            $exitCode = 1;
            header("location: ../workingTimeRecording.php?error=onlyBeginInput");
            
        }

        //Es wurde nur eine Endzeit bei (min) einer Projektaufgabe eigetragen
        elseif(onlyEndInput($beginTime, $endTime) == true){
            $exitCode = 1;
            header("location: ../workingTimeRecording.php?error=onlyEndInput");
        }
        
        //Die Endzeit liegt vor der Startzeit
        elseif(beginIsAfterEnd($beginTime, $endTime) == true){
            $exitCode = 1;
            header("location: ../workingTimeRecording.php?error=beginIsAfterEnd");
            
        }

        //Es liegt eine Überlappung bei (min) zwei Projekten vor --> hier mit Array arbeiten
        elseif(overlappingProjects($beginTime, $endTime, $beginTimeA, $endTimeA) == true){
            $exitCode = 1;
            header("location: ../workingTimeRecording.php?error=overlappingProjects");
            
        }

        //Für das Projekt wurde keine gar keine Zeit eingetragen --> kein Fehler, aber auch trotzdem keine Speicherung im Array
        elseif(bothTimesEmpty($beginTime, $endTime) == true){
            $exitCode = 0;
            unset($projectIDArray[$i]);
            unset($projectTaskIDArray[$i]);
        }

        //Kein Fehler --> Speicherung in Array
        else{    
            array_push($beginTimeA, $beginTime);
            array_push($endTimeA, $endTime);   
        }

    } //hier endet die for-Schleife

        

    //Es wurde gar keine Zeit eingetragen (Array leer) --> nur updateLastDateEntered
    if(emptyArray($beginTimeA, $endTimeA) == true && $exitCode == 0 ){
        echo "Test Empty Array";
        updateLastDateEntered($conn, $recordingDate, $pnr);
        if($recordingDate <= $datum = date("Y-m-d",$timestamp)) {
            header("location: ../workingTimeRecording.php"); 
        }
        else{
            if($_SESSION['projectManager'] = "projektManager")
            header("location: ../../projectManagerMenu.php");
            else{
                header("location: ../../employeeMenu.php");
            }
            
        }
    }
    //Zeiten aus Array abspeichern 
    elseif($exitCode == 0){
        for($i=0; $i < count($projectIDArray); $i++){
          saveTimeRecoring($conn, $pnr, $projectIDArray[$i], $projectTaskIDArray[$i], $recordingDate,
                           $beginTimeA[$i] = str_replace(':', '', $beginTimeA[$i])."00", $endTimeA[$i]= str_replace(':', '', $endTimeA[$i])."00");
          //echo "<br>".$beginTimeA[$i]; 
          //echo "<br>".$endTimeA[$i];
          
        }
        updateLastDateEntered($conn, $recordingDate, $pnr);
        if($recordingDate < $datum = date("Y-m-d",$timestamp)) {
            header("location: ../workingTimeRecording.php"); 
        }
        else{
            if($_SESSION['projectManager'] = "projektManager"){
                header("location: ../../menu/projectManagerMenu.php");
            }            
            else{
                header("location: ../../menu/employeeMenu.php");
            }    
        }

    }
    else{
        //do nothing, denn es wird bereits Fehler ausgegeben, wenn exitCode = 1
    }
} 
