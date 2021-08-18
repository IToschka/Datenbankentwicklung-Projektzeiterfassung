<?php

function emptyInput($firstname, $lastname, $password, $coreTimeFrom, $coreTimeTo,
    $hiringDate, $weeklyWorkingHours) {
        $result;
        if(empty($firstname) || empty($lastname) || empty($password) || empty($coreTimeFrom)
        || empty($coreTimeTo)|| empty($hiringDate)|| empty($weeklyWorkingHours)) {
            $result = true;
        }
        else {
            $result = false;
        }
        return $result;
    }

function invalidFirstname($firstname){
    $result;
    if(!preg_match("/^[a-zA-ZäöüÄÖÜ?ß-]*$/", $firstname) && strlen(trim($firstname))<2) {
            $result = true;
    }
    else{
         $result = false;
        }
    return $result;
}

function invalidLastname($lastname){
    $result;
    if(!preg_match("/^[a-zA-ZäöüÄÖÜ?ß-]*$/", $lastname) && strlen(trim($lastname))<2) {
            $result = true;
    }
    else{
         $result = false;
        }
    return $result;
}

function invalidPassword($password){
    $result;
    if(strlen($password)<8 || !preg_match("/[0-9]/", $password) || !preg_match("/[A-Z]/", $password) ||
        !preg_match("/[a-z]/", $password) || !preg_match("/[^\w]/", $password)) {
            $result = true;
    }
    else{
         $result = false;
        }
    return $result;
}

function invalidCoreTime($coreTimeFrom, $coreTimeTo){
    $result;
    if($coreTimeFrom>$coreTimeTo) {
            $result = true;
    }
    else{
         $result = false;
        }
    return $result;
}

function invalidWeeklyWorkingHours($weeklyWorkingHours){
    $result;
    if($weeklyWorkingHours<10) {
            $result = true;
    }
    else{
         $result = false;
        }
    return $result;
}

function createEmployee($conn, $pnr, $firstname, $lastname, $coreTimeFrom, $coreTimeTo,
$hiringDate, $weeklyWorkingHours, $projectManager){
    $sql = "INSERT INTO employee (PNR, Firstname, Lastname, CoreWorkingTimeFrom,
    CoreWorkingTimeTo, HiringDate, WeeklyWorkingHours, ProjectManager) VALUES (?, ?, ?, ?, ?, ?, ?,?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../createEmployee.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssssssss",$pnr, $firstname, $lastname, $coreTimeFrom, $coreTimeTo,
    $hiringDate, $weeklyWorkingHours, $projectManager);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function createLogin($conn, $pnr, $password){
    $sql = "INSERT INTO login (PNR, Password) VALUES (?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../createEmployee.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss",$pnr, $password);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../createEmployee.php?error=none?");
    exit();
}








function emptyInput($pnr) {
        $result;
        if(empty($pnr)) {
            $result = true;
        }
        else {
            $result = false;
        }
        return $result;
    }

    function pnrNotExists($conn, $pnr){
        $sql = "SELECT * FROM employee WHERE PNR = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../updateEmployee.php?error=pnrNotExists");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $pnr);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);


        if(!mysqli_fetch_assoc($resultData)) {
                $result = true;
        }
        else{
             $result = false;
            }
        return $result;
        mysqli_stmt_close($stmt);
    }



    function updateEmployee($conn, $coreTimeFrom, $coreTimeTo, $weeklyWorkingHours, $pnr){

        if(!empty($coreTimeFrom)){
        $sql = "UPDATE employee SET CoreWorkingTimeFrom = ? WHERE PNR = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../updateEmployee.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ss", $coreTimeFrom, $pnr);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        }

        if(!empty($coreTimeTo)){
            $sql = "UPDATE employee SET CoreWorkingTimeTo = ? WHERE PNR = ?;";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                header("location: ../updateEmployee.php?error=stmtfailed");
                exit();
            }

            mysqli_stmt_bind_param($stmt, "ss", $coreTimeTo, $pnr);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            }

        if(!empty($weeklyWorkingHours)){
        $sql = "UPDATE employee SET WeeklyWorkingHours = ? WHERE PNR = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../updateEmployee.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ss", $weeklyWorkingHours, $pnr);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        }

        header("location: ../updateEmployee.php?error=none");
        exit();

    }














    function pnrNotExists($conn, $pnr){
        $sql = "SELECT * FROM employee WHERE PNR = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../deleteEmployee.php?error=pnrNotExists");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $pnr);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);


        if(!mysqli_fetch_assoc($resultData)) {
                $result = true;
        }
        else{
             $result = false;
            }
        return $result;
        mysqli_stmt_close($stmt);
    }



    function deleteEmployee($conn, $pnr){
        $sql = "DELETE FROM employee WHERE PNR = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../deleteEmployee.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s",$pnr);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("location: ../deleteEmployee.php?error=none");
        exit();
    }
