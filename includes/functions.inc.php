<?php
//Autor der Datei Tamara Romer
function login($conn, $pnr, $password){

  $sql = "SELECT * FROM login WHERE PNR = ?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../login.php?error=stmtfailed");
      exit();
  }

  mysqli_stmt_bind_param($stmt, "s", $pnr);
  mysqli_stmt_execute($stmt);

  $resultData = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($resultData);
  $passwordHashed = $row['Password'];
  echo $passwordHashed;
  $checkPassword = password_verify($password, $passwordHashed);

  if($checkPassword === false){
    return true;
  }else{
    session_start();
    $_SESSION["pnr"] = $row['PNR'];
    mysqli_stmt_close($stmt);
    return false;
  }
}

function employeeRole($conn, $pnr){
  $sql = "SELECT ProjectManager FROM employee WHERE PNR = ?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../login.php?error=stmtfailed");
      exit();
  }
  mysqli_stmt_bind_param($stmt, "s", $pnr);
  mysqli_stmt_execute($stmt);

  $resultData = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($resultData);

  if($row['ProjectManager'] == 1) {
    return true;
  }else{
    return false;
  }

  mysqli_stmt_close($stmt);
}
