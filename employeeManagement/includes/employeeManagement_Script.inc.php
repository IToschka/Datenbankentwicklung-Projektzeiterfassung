<?php
//Autor der Datei Tamara Romer
include_once '../../includes/dbh.inc.php';
include_once 'employeeManagement_Functions.inc.php';

if (isset($_POST['button_createEmployee'])) {
    $sql = "SELECT GeneratePNR() AS PNR;";
    $result = mysqli_query($conn, $sql);
    $row= mysqli_fetch_assoc($result);
    $pnr = $row['PNR'];

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];
    $passwordRepeat = $_POST['passwordRepeat'];
    $coreTimeFrom = $_POST['coreTimeFrom'];
    $coreTimeTo = $_POST['coreTimeTo'];
    $hiringDate = $_POST['hiringDate'];
    $weeklyWorkingHours = $_POST['weeklyWorkingHours'];
    if (isset($_POST['projectManager'])){
        $projectManager = true;
    }else{
        $projectManager = false;
    }

    $lastDateEntered= getLastDateEntered($hiringDate);

    if(invalidFirstname($firstname) !== false){
        header("location: ../createEmployee.php?error=invalidFirstname");
        exit();
    }

    if(invalidLastname($lastname) !== false){
        header("location: ../createEmployee.php?error=invalidLastname");
        exit();
    }

    if(invalidPassword($password) !== false){
        header("location: ../createEmployee.php?error=invalidPassword");
        exit();
    }

    if(passwordMatch($password, $passwordRepeat) !== false){
      header("location: ../createEmployee.php?error=passwordsDontMatch");
      exit();
    }

    if(invalidCoreTime($coreTimeFrom, $coreTimeTo) !== false){
        header("location: ../createEmployee.php?error=invalidCoreTime");
        exit();
    }

    if(invalidWeeklyWorkingHours($weeklyWorkingHours) !== false){
        header("location: ../createEmployee.php?error=invalidWeeklyWorkingHours");
        exit();
    }


    createEmployee($conn, $pnr, $firstname, $lastname, $coreTimeFrom, $coreTimeTo,
    $hiringDate, $weeklyWorkingHours, $projectManager, $lastDateEntered);

    createLogin($conn, $pnr, $password);

} elseif(isset($_POST['button_updateEmployee'])){
    $pnr = $_POST['pnr'];
    $coreTimeFrom = $_POST['coreTimeFrom'];
    $coreTimeTo = $_POST['coreTimeTo'];
    $weeklyWorkingHours = $_POST['weeklyWorkingHours'];

    if(pnrNotExistsUpdate($conn, $pnr) !== false){
      header("location: ../updateEmployee.php?error=pnrNotExists");
      exit();
    }

    if(invalidWeeklyWorkingHours($weeklyWorkingHours) !== false){
        header("location: ../updateEmployee.php?error=invalidWeeklyWorkingHours");
        exit();
    }

    updateEmployee($conn, $coreTimeFrom, $coreTimeTo, $weeklyWorkingHours, $pnr);

} elseif(isset($_POST['button_deleteEmployee'])) {
    $pnr = $_POST['pnr'];
    session_start();


    if(pnrNotExistsDelete($conn, $pnr) !== false){
      //header("location: ../deleteEmployee.php?error=pnrNotExists");
      exit();
    }

    if(pnrNotExistsDelete($conn, $pnr) !== false){
      //header("location: ../deleteEmployee.php?error=pnrNotExists");
      exit();
    }

    if(pnrNotExistsDelete($conn, $pnr) !== false){
      //header("location: ../deleteEmployee.php?error=pnrNotExists");
      exit();
    }

    //deleteEmployee($conn, $pnr);

    $loggedPnr = $_SESSION['pnr'];
    //loggedpnr füllen
    if(deletedEmployeeIsLoggedEmployee($pnr, $loggedPnr) !== false){
      header("location: ../../login.php");
      exit();
    }
}
