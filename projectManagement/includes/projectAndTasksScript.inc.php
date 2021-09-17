<?php
//Autor: Katja Frei

session_start();
include_once '../../includes/dbh.inc.php';
include_once 'projectManagementFunctions.inc.php';



//Create
if (isset($_POST['button_createTasks'])) {
    $taskID = 0;
    $tasks = array();
    $projektID = $_SESSION['ProjectID'];


    for ($i = 0; $i < $_SESSION['amountTasks']; $i++) {
        array_push($tasks, $_POST["task{$i}"]);

    }

    foreach($tasks as $task){
      $taskID++;
      echo $projektID . "<br>";

      echo $taskID . "<br>";
      echo $task . "<br>";
      createTasks($conn, $taskID, $projektID, $task);
    }

}

//----------------------------------------------------------------

//Change


//----------------------------------------------------------------

//Copy

$error ="";
if(isset($_POST['button_copyProject'])) {


  $projectName = $_POST['projectName'];
  $beginDate = $_POST['beginDate'];
  $amountTasks= $_POST['amountTasks'];
  $projectManager = $_POST['projectManager'];

  $date = date("d.m.Y");
  $dateTimestamp = strtotime($date);
  $beginDateTimestamp = strtotime($beginDate);


  $error = invalidDate($dateTimestamp, $beginDateTimestamp);

    if ($error == "") {
        $error = titleAlreadyExists($conn, $projectName);
    }
    // Projekt speichern
    if ($error == "") {
        $error = createProject($conn, $projectName, $beginDate, $projectManager);
    }

    $taskID = 0;
    $tasks = array();
    $projektID = getProjectID($conn);


    for ($i = 0; $i < $_SESSION['amountTasks']; $i++) {
        array_push($tasks, $_POST["task{$i}"]);

    }

    foreach($tasks as $task){
      $taskID++;
      createTasks($conn, $taskID, $projektID, $task);
    }
}



//----------------------------------------------------------------

//delete

elseif(isset($_POST['button_delete'])) {
    $projectID = $_POST['projectID'];

    if(invalidProjectID($conn, $projectID) !== false) {
        header("location: ../projectsAndTasksDelete.php?error=invalidProjectID");
        exit();
    }

    deleteProject($conn, $projectID);
}

//----------------------------------------------------------------

//Connect and disconnect



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
if(alreadyExisting($conn, $pnr, $projectID) !== false) {
    header("location: ../employeesAndProjects.php?error=alreadyExisting");
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
if(noSuchCombination($conn, $pnr, $projectID) !== false) {
    header("location: ../employeesAndProjects.php?error=noSuchCombination");
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
