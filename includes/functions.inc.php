<?php

function emptyInput($firstname, $lastname, $password, $coreTimeFrom, $coreTimeTo,
    $hireDate, $weeklyWorkingHours){
        $result;
        if(empty($firstname) || empty($lastname) || empty($password) || empty($coreTimeFrom)
        || empty($coreTimeTo)|| empty($hireDate)|| empty($weeklyWorkingHours)) {
            $result = true;
        }
        esle{
            $result = false;
        }
        return $result;
    }


function invalidFirstname($firstname){
    $result;
    if(!preg_match("/^[a-zA-Z]*$/", $firstname)) {
         $result = true;
    }
    esle{
         $result = false;
     }
    return $result;
 }

function createEmployee($conn, $pnr, $firstname, $lastname, $password, $coreTimeFrom, $coreTimeTo,
$hireDate, $weeklyWorkingHours, $projectManager){
    $sql = "INSERT INTO employee (PNR, Firstname, Lastname, CoreWorkingTimeFrom, 
    CoreWorkingTimeTo, HiringDate, ProjectManager) VALUES (?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../CreateEmployee.php?error=stmtfailed");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssssss",$pnr, $firstname, $lastname, $hashedPassword, $coreTimeFrom, $coreTimeTo,
    $hireDate, $weeklyWorkingHours, $projectManager);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../CreateEmployee.php?error=none");
    exit();
}

