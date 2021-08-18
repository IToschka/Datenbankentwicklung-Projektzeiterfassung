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
