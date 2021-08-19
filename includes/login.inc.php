<?php

include_once 'dbh.inc.php';

if (isset($_POST['button_login'])) {

  $pnr = $_POST['pnr'];
  $password = $_POST['password'];

  require_once 'functions.inc.php';

  if(emptyInput($pnr, $password) !== false){
      header("location: ../login.php?error=emptyInput");
      exit();
  }

  if(login($conn, $pnr, $password)!== false){
      header("location: ../login.php?error=incorrectLoginData");
      exit();
  }else{
    echo "Angemeldet";
    header("location: ../employeeManagement/createEmployee.php");
  }

} else {
  header("location: ../login.php");
  exit();
}
