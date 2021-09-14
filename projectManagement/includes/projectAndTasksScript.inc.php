<?php
include_once '../../includes/dbh.inc.php';
include_once 'projectManagementFunctions.inc.php';



if (isset($_POST['button_createProject'])) {

  $projectName = $_POST['projectname'];
  $beginDate = $_POST['beginDate'];
  $amountTasks= $_POST['amountTasks'];
  $projectManager = $_POST['projectManager'];

  $date = date("d.m.Y");
        
  
  //$task = $_POST['task'];


if(invalidDate($date, $beginDate) !== false) {
    header("location: ../projectsAndTasksNew.php?error=invalidDate");
    exit();
}
 if(invalidProjectManagerPNR($conn, $projectManager) !== false) {
    header("location: ../projectsAndTasksNew.php?error=invalidProjectManagerPNR");
    exit();
}

if(noNegativeProjectManagerPNR($projectManager) !== false) {
    header("location: ../projectsAndTasksNew.php?error=noNegativeProjectManagerPNR");
    exit();
} 

if(noNegativeAmountTasks($amountTasks) !== false) {
    header("location: ../projectsAndTasksNew.php?error=noNegativeAmountTasks");
    exit();
} 

createProject($conn, $projectName, $beginDate, $projectManager);

}


elseif (isset($_POST['button_createTasks'])) {
    $task = array();
    
    createTasks($task); 
} 

//----------------------------------------------------------------

//Projekt ändern
elseif(isset($_POST['button_choose'])) {
    $projectID = $_SESSION[$_POST['projectID']]; //sessionvariable
    

    /*if (invalidProjectID($conn, $projectID) !== false) {
        header("location: ../projectsAndTasksChange.php?error=invalidProjectID");
    } */ //Fehler schlägt immer an
    
        header("location: ../projectsAndTasksChange2.php");
    
}


elseif(isset($_POST['button_change'])) {
    $projectName = $_POST['projectName'];
  $beginDate = $_POST['beginDate'];
  $amountTasks= $_POST['amountTasks'];
  $projectManager = $_POST['projectManager'];

    header("location: ../projectsAndTasksChange3.php");

  

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