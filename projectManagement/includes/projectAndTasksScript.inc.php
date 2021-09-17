<?php
//Autor: Katja Frei

session_start();
include_once '../../includes/dbh.inc.php';
include_once 'projectManagementFunctions.inc.php';



//Create

if (isset($_POST['button_createTasks'])) {
   
   
    $tasks = array();
    for ($i = 0; $i < $_SESSION['amountTasks']; $i++) {
        array_push($tasks, $_POST["task{$i}"]);
        
    }
    $projectID = getProjectID($conn);
    //$projectID = $row['ProjectID'];

    if ($projectID > 0) {
        for($i = 0; $i < count($tasks); $i++) {
            createTasks($conn, $tasks[$i], $projectID); 
        }
    }
    
} 

//----------------------------------------------------------------

//Update


elseif(isset($_POST['button_change'])) {
    $projectName = $_POST['projectName'];
    $beginDate = $_POST['beginDate'];
    $amountTasks= $_POST['amountTasks'];
    $projectManager = $_POST['projectManager'];

    $date = date("d.m.Y");
                           
              if(invalidDate($date, $beginDate) !== false) {
                  header("location: ../projectsAndTasksNew.php?error=invalidDate");
                  exit();
              }
               if(invalidProjectManagerPNR($conn, $projectManager) !== false) {
                  header("location: ../projectsAndTasksNew.php?error=invalidProjectManagerPNR");
                  exit();
              }
              if(noAccess($conn,$projectID, $projectManager) !== false) {
                header("location: ../projectsAndTasksNew.php?error=invalidProjectManagerPNR");
                exit();
            }
 

              updateProject($conn, $projectName, $beginDate, $projectManager);
}

elseif(isset($_POST['button_copy'])) {
    $projectName = $_POST['projectName'];
    $beginDate = $_POST['beginDate'];
    $amountTasks= $_POST['amountTasks'];
    $projectManager = $_POST['projectManager'];

    $date = date("d.m.Y");
                           
              if(invalidDate($date, $beginDate) !== false) {
                  header("location: ../projectsAndTasksNew.php?error=invalidDate");
                  exit();
              }
               if(invalidProjectManagerPNR($conn, $projectManager) !== false) {
                  header("location: ../projectsAndTasksNew.php?error=invalidProjectManagerPNR");
                  exit();
              }

              createProject($conn, $projectName, $beginDate, $projectManager);
}

elseif(isset($_POST['button_copy'])) {
    $tasks = array();
    for ($i = 0; $i < $_SESSION['amountTasks']; $i++) {
        array_push($tasks, $_POST["task{$i}"]);
    }
    
    for($i = 0; $i < count($tasks); $i++) {
        createTasks($conn, $tasks[$i]); 
    }
} 

//----------------------------------------------------------------

elseif(isset($_POST['button_delete'])) {
    $projectID = $_POST['projectID'];
    
    //Fehler
    if(invalidProjectID($conn, $projectID) !== false) {
        header("location: ../projectsAndTasksDelete.php?error=invalidProjectID");
        exit();
    }

    deleteProject($conn, $projectID);
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
