<?php
include_once '../../includes/dbh.inc.php';
include_once 'projectManagementFunctions.inc.php';

if (isset($_POST['button_createProject'])) {

  $projectName = $_POST['projectname'];
  $beginDate = $_POST['beginDate'];
  $amountTasks= $_POST['amountTasks'];

//create functions
if(emptyInput($projectName, $beginDate) !== false){
    header("location: ../projectsAndTasksNew.php?error=emptyInput");
    exit();
}

createProject($conn, $projectName, $beginDate);
}



/*
Anderes File

include_once '../../includes/dbh.inc.php';

if (isset($_POST['button_create'])) {
    $sql = "SELECT GeneratePNR() AS PNR;";
    $result = mysqli_query($conn, $sql);
    $row= mysqli_fetch_assoc($result);
    $pnr = $row['PNR'];

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];
    $coreTimeFrom = $_POST['coreTimeFrom'];
    $coreTimeTo = $_POST['coreTimeTo'];
    $hiringDate = $_POST['hiringDate'];
    $weeklyWorkingHours = $_POST['weeklyWorkingHours'];
    if (isset($_POST['projectManager'])){
        $projectManager = true;
    }else{
        $projectManager = false;
    }

*/
