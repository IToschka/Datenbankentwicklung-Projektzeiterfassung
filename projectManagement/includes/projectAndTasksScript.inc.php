<?php
session_start();
include_once '../../includes/dbh.inc.php';
include_once 'projectManagementFunctions.inc.php';





elseif (isset($_POST['button_createTasks'])) {
    $tasks = array();
    for ($i = 0; $i < $_SESSION['amountTasks']; $i++) {
        array_push($tasks, $_POST["task{$i}"]);
    }
    //$tasks = array($_POST['task']);
    
    for($i = 0; $i < count($tasks); $i++) {
        createTasks($conn, $tasks[$i]); 
    }
} 

//----------------------------------------------------------------

//Projekt ändern
elseif(isset($_POST['button_choose'])) {
    $projectID = $_SESSION[$_POST['projectID']]; //sessionvariable
    

    /*if (invalidProjectID($conn, $projectID) !== false) {
        header("location: ../projectsAndTasksChange.php?error=invalidProjectID");
    } */ //Fehler schlägt immer an
    
        header("location: ../projectsAndTasksChange.php");
    
}


elseif(isset($_POST['button_change'])) {
    $projectName = $_POST['projectName'];
  $beginDate = $_POST['beginDate'];
  $amountTasks= $_POST['amountTasks'];
  $projectManager = $_POST['projectManager'];

    header("location: ../projectsAndTasksChange.php");

  

}

//----------------------------------------------------------------



//----------------------------------------------------------------

//Mitarbeiter Projekten zuordnen



elseif (isset($_POST['button_connect'])) {
    $pnr = $_POST["pnr"];
    $projectID = $_POST['projectID'];

if(invalidpnr($conn, $pnr) !== false) {
    header("location: ../employeesAndProjects.php?error=invalidpnr");
    exit();
}
if(invalidProjectID($conn, $projectID) !== false) {
    header("location: ../employeesAndProjects.php?error=invalidProjectID");
    exit();
} 
if(noNegativePNR($pnr) !== false) {
    header("location: ../employeesAndProjects.php?error=noNegativePNR");
    exit();
} 

createConnection($conn, $pnr, $projectID);
}

elseif(isset($_POST['button_disconnect'])) {
    $pnr = $_POST["pnr"];
    $projectID = $_POST['projectID'];

if(invalidpnr($conn, $pnr) !== false) {
    header("location: ../employeesAndProjects.php?error=invalidpnr");
    exit();
}
if(invalidProjectID($conn, $projectID) !== false) {
    header("location: ../employeesAndProjects.php?error=invalidProjectID");
    exit();
} 
deleteConnection($conn, $pnr, $projectID);
}


elseif(isset($_POST['button_projectsAndTasksMenu'])){
    header("location: ../projectsAndTasksMenu.php");
    exit();
}

elseif(isset($_POST['button_projectManagerMenu'])){
    header("location: ../projectManagerMenu.php");
    exit();
}
elseif(isset($_POST['button_disconnect'])) {
    $pnr = $_POST["pnr"];
    $projectID = $_POST['projectID'];

    deleteConnection($conn, $pnr, $projectID);
}