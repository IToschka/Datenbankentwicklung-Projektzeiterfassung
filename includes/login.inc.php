<?php
session_start();
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
  }
  else{
      if(employeeRole($conn, $pnr) == true){
        $_SESSION['projectManager'] = "projektManager";
        header("location: ../startMenu/projectManagerMenu.php");
      }else{
        header("location: ../startMenu/employeeMenu.php");
      }
  }

} else {
    header("location: ../login.php");
    exit();
}
