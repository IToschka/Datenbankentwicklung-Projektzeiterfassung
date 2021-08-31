<?php
include_once '../../includes/dbh.inc.php';
include_once 'projectManagementFunctions.inc.php';

if (isset($_POST['button_createProject'])) {

  $projectName = $_POST['projectname'];
  $beginDate = $_POST['beginDate'];
  $amountTasks= $_POST['amountTasks'];
  $projectManager = $_POST['projectManager'];
  
  date_default_timezone_set("Europe/Berlin");
$timestamp = time();
$date = date("d.m.Y");

$task = $_POST['task'];

//create functions
if(invalidDate($beginDate) !== false) {
    header("location: ../projectsAndTaskNew.php?error=invalidDate");
    exit();
}
 if(invalidProjectManagerPNR($projectManager)) {
    header("location: ../projectAndTasksNew.php?error=invalidProjectManagerPNR");
    exit();
}

if(numericProjectManagerPNR($projectManager) !== false) {
    header("location: ../projectsAndTaskNew.php?error=numericProjectManager");
    exit();
}

createProject($conn, $projectName, $beginDate, $projectManager);
}

if (isset($_POST['button_createTasks'])) {
    createTasks($task);
}

//----------------------------------------------------------------

//Mitarbeiter Projekte zuordnen

if (isset($_POST['button_connect'])) {
    $pnr = $_POST["pnr"];
    $projectID = $_POST['projectID'];

    createConnection($conn, $pnr, $projectID);
}

