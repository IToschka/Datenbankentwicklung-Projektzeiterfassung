<?php
include_once '../../includes/dbh.inc.php';
include_once 'projectManagementFunctions.inc.php';

if (isset($_POST['button_createProject'])) {

  $projectName = $_POST['projectname'];
  $beginDate = $_POST['beginDate'];
  $amountTasks= $_POST['amountTasks'];
  $projectManager = $_POST['projectManager'];
  
  //$task = $_POST['task'];


if(invalidDate($date, $beginDate) !== false) {
    header("location: ../projectsAndTasksNew.php?error=invalidDate");
    exit();
}
 if(invalidProjectManagerPNR($conn, $projectManager)) {
    header("location: ../projectsAndTasksNew.php?error=invalidProjectManagerPNR");
    exit();
}

if(numericProjectManagerPNR($projectManager) !== false) {
    header("location: ../projectsAndTasksNew.php?error=numericProjectManagerPNR");
    exit();
} 

createProject($conn, $projectName, $beginDate, $projectManager);
countTasks($amountTasks);
}


elseif (isset($_POST['button_createTasks'])) {
    //createTasks($task); 
} 

//----------------------------------------------------------------

//Projekt kopieren
elseif(isset($_POST['button_change'])) {
    $projectName = $_POST['projectname'];
  $beginDate = $_POST['beginDate'];
  $amountTasks= $_POST['amountTasks'];
  $projectManager = $_POST['projectManager'];

  changeProject($conn, $projectName, $beginDate, $projectManager);

}

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
if(numericPNR($pnr) !== false) {
    header("location: ../employeesAndProjects.php?error=inumericPNR");
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