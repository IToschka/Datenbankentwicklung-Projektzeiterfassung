<?php
//Autor der Datei Tamara Romer
/* Die Datei enhält die Funktionen und Fehlermeldungen zum Anlegen, zum Ändern und Löschen eines Mitarbeiters
Iin der Datenbank ist ein Trigger  von Katja hinterlegt, der beim Löschen eines Projektleiters, die betroffenen Projekte dem Admin zuweist*/


//Funktionen für Mitarbeiter anlegen
function invalidFirstname($firstname){
    if(!preg_match("/^[a-zA-ZäöüÄÖÜß \-]+$/", $firstname)) {
        return true;
    }
    else{
        return false;
        }
}

function invalidLastname($lastname){
    if(!preg_match("/^[a-zA-ZäöüÄÖÜß \-]+$/", $lastname)) {
          return true;
    }
    else{
         return false;
        }
}

function invalidPassword($password){
    if(strlen($password)<8 || !preg_match("/[0-9]/", $password) || !preg_match("/[A-Z]/", $password) ||
      !preg_match("/[a-z]/", $password) || !preg_match("/[^\w]/", $password)) {
        return true;
    }else{
        return false;
    }
}


function passwordMatch($password, $passwordRepeat){
  if($password !== $passwordRepeat){
    return true;
  }else{
    return false;
  }
}


function invalidCoreTime($coreTimeFrom, $coreTimeTo){
    if($coreTimeFrom>$coreTimeTo) {
        return true;
    }else{
        return false;
    }
}

function invalidWeeklyWorkingHours($weeklyWorkingHours){
    if($weeklyWorkingHours<10) {
        return true;
    }else{
        return false;
    }
}


function getLastDateEntered($hiringDate){

  if(date('l', strtotime($hiringDate)) == 'Monday') {
      $lastDateEntered = date('Y-m-d', strtotime("-3 day", strtotime($hiringDate)));
  }elseif(date('l', strtotime($hiringDate)) == 'Sunday') {
      $lastDateEntered = date('Y-m-d', strtotime("-2 day", strtotime($hiringDate)));
  }else {
      $lastDateEntered = date('Y-m-d', strtotime("-1 day", strtotime($hiringDate)));
  }
  return $lastDateEntered;
}



function createEmployee($conn, $pnr, $firstname, $lastname, $coreTimeFrom, $coreTimeTo,
$hiringDate, $weeklyWorkingHours, $projectManager, $lastDateEntered){
    $sql = "INSERT INTO employee (PNR, Firstname, Lastname, CoreWorkingTimeFrom,
    CoreWorkingTimeTo, HiringDate, WeeklyWorkingHours, ProjectManager, LastDateEntered) VALUES (?, ?, ?, ?, ?, ?, ?,?,?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../createEmployee.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sssssssss",$pnr, $firstname, $lastname, $coreTimeFrom, $coreTimeTo,
    $hiringDate, $weeklyWorkingHours, $projectManager, $lastDateEntered);
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

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ss",$pnr, $hashedPassword);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../createEmployee.php?error=none");
}




//Funktionen für Mitarbeiter Ändern
function pnrNotExistsUpdate($conn, $pnr){
    $sql = "SELECT PNR FROM employee WHERE PNR = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../updateEmployee.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $pnr);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    if(!mysqli_fetch_assoc($resultData)) {
      mysqli_stmt_close($stmt);
      return true;
    }else{
      mysqli_stmt_close($stmt);
      return false;
    }
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
}




//Funktionen für Mitarbeiter löschen
function pnrNotExistsDelete($conn, $pnr){
    $sql = "SELECT PNR FROM employee WHERE PNR = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../deleteEmployee.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $pnr);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    if(!mysqli_fetch_assoc($resultData)) {
      mysqli_stmt_close($stmt);
      return  true;
    }else{
      mysqli_stmt_close($stmt);
      return  false;
    }
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
}


function deletedEmployeeIsLoggedEmployee($pnr,$loggedPnr){
  if($pnr == $loggedPnr){
      return true;
  }else{
      return false;
  }
}
