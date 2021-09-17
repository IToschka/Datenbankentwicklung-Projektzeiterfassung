<?php
//Autor: Katja Frei

session_start();
include_once '../../includes/dbh.inc.php';
include_once 'projectManagementFunctions.inc.php';



//Create
if (isset($_POST['button_createTasks'])) {
    $projectID = $_SESSION['projectID'];
    $taskID = 0;
    $tasks = array();
    for ($i = 0; $i < $_SESSION['amountTasks']; $i++) {
        array_push($tasks, $_POST["task{$i}"]);
        
    }

    
        for($i = 0; $i < count($tasks); $i++) {
            $taskID++;
           // createTasks($conn, $taskID, $projectID, $tasks[$i]); 
            
        }
    
    
} 

//----------------------------------------------------------------

//Change
if(isset($_POST['button_change'])) {
    $task = $_POST['task'];
    
    $projectID  = getProjectID($conn);
    echo $projectID;

    updateTask($conn, $task, $projectID);
}

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
      
  
  
if($error == "") {
$error = titleAlreadyExists($conn, $projectName);
  
}

    if ($error == "") {
        $error = createProject($conn, $projectName, $beginDate, $projectManager);
  
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
