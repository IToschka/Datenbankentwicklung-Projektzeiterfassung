<?php

function emptyInput($pnr, $password) {
        $result;
        if(empty($pnr) || empty($password)) {
            $result = true;
        }
        else {
            $result = false;
        }
        return $result;
    }



function login($conn, $pnr, $password){

  $sql = "SELECT * FROM login WHERE PNR = ? AND Password = ?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../login.php?error=stmtfailed");
      exit();
  }
  mysqli_stmt_bind_param($stmt, "ss", $pnr, $password);
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
