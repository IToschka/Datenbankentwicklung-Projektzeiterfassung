<?php
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

    require_once 'createFunctions.inc.php';

    if(emptyInput($firstname, $lastname, $password, $coreTimeFrom, $coreTimeTo,
    $hiringDate, $weeklyWorkingHours) !== false){
        header("location: ../createEmployee.php?error=emptyInput");
        exit();
    }

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

    if(invalidCoreTime($coreTimeFrom, $coreTimeTo) !== false){
        header("location: ../createEmployee.php?error=invalidCoreTime");
        exit();
    }

    if(invalidWeeklyWorkingHours($weeklyWorkingHours) !== false){
        header("location: ../createEmployee.php?error=invalidWeeklyWorkingHours");
        exit();
    }


    createEmployee($conn, $pnr, $firstname, $lastname, $coreTimeFrom, $coreTimeTo,
    $hiringDate, $weeklyWorkingHours, $projectManager);

    createLogin($conn, $pnr, $password);
    }







    elseif(isset($_POST['button_update'])){
        $pnr = $_POST['pnr'];
        $coreTimeFrom = $_POST['coreTimeFrom'];
        $coreTimeTo = $_POST['coreTimeTo'];
        $weeklyWorkingHours = $_POST['weeklyWorkingHours'];

        require_once 'updateFunctions.inc.php';

        if(emptyInput($pnr) !== false){
        header("location: ../updateEmployee.php?error=emptyInput");
        exit();
        }

        if(PnrNotExists($conn, $pnr) !== false){
            header("location: ../updateEmployee.php?error=pnrNotExists");
            exit();
        }


        if(invalidWeeklyWorkingHours($weeklyWorkingHours) !== false){
            header("location: ../updateEmployee.php?error=invalidWeeklyWorkingHours");
            exit();
        }

        updateEmployee($conn, $coreTimeFrom, $coreTimeTo, $weeklyWorkingHours, $pnr);




    }
    elseif(isset($_POST['button_delete'])) {
        $pnr = $_POST['pnr'];

        require_once 'deleteFunctions.inc.php';

        if(emptyInput($pnr) !== false){
        header("location: ../deleteEmployee.php?error=emptyInput");
        exit();
        }

        if(PnrNotExists($conn, $pnr) !== false){
            header("location: ../deleteEmployee.php?error=pnrNotExists");
            exit();
        }

        deleteEmployee($conn, $pnr);
        }
        elseif(isset($_POST['button_EmployeeMenu'])){
            header("location: ../employeeManagementMenu.php");
            exit();

    }
