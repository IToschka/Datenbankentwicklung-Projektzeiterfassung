<?php

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
            header("location: ../updateEmployee.php?error=stmtfailed");
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
