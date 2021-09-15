<?php
//Autor der Datei Tamara Romer
include_once 'calculationOfEvaluationDataFunctions.inc.php';

//Fehermeldungen
function invalidEvaluationDate($evaluationFrom, $evaluationTo){
    if($evaluationFrom>$evaluationTo) {
        $result = true;
    }else{
        $result = false;
    }

  return $result;
}


function invalidPnr($evaluatedPnr){
    if(!preg_match("/[0-9]/", $evaluatedPnr)) {
        $result = true;
    }else{
        $result = false;
    }
      
  return $result;
}

function pnrNotExists($conn, $evaluatedPnr){
    $sql = "SELECT * FROM employee WHERE PNR = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../updateEmployee.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $evaluatedPnr);
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


//Funktionen für die Gesamtübersicht der Abweichungen eines Mitarbeites über alle Projekte

//Ermittelt wie viele Tage der Mitarbeiter im angegebenen Zeitraum gearbeitet hat
function getWorkingDays($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr){

    $sql = "SELECT COUNT(DISTINCT RecordingDate) AS NumberOfWorkingDays
            FROM timerecording
            WHERE RecordingDate BETWEEN ?
            AND ? AND PNR = ? ;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../evaluationPerEmployee.php?error=stmtfailed");
        exit();

    }

    mysqli_stmt_bind_param($stmt, "sss", $evaluationFrom, $evaluationTo, $evaluatedPnr);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $row= mysqli_fetch_assoc($resultData);
    $numberOfWorkingDays = $row['NumberOfWorkingDays'];

  return $numberOfWorkingDays;

}

//Evaluiert die Summe, den Durchschnitt, das Minimum, das Maximum und die Standardabweichung
//der Wochenstundenabweichungen eines Mitarbeiters über alle Projekte
function evaluateWeeklyWorkingsHoursPerEmployee($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr){

    $sql = "SELECT timerecording.PNR, WEEKOFYEAR(RecordingDate) AS RecordingWeek,
            YEAR(RecordingDate) AS RecordingYear,
            TIMEDIFF((CAST(WeeklyWorkingHours*10000 AS TIME)), (CAST((SUM(TIMEDIFF(TaskEnd, TaskBegin)))AS TIME))) AS Deviation
            FROM timerecording, employee WHERE timerecording.PNR = employee.PNR
            AND RecordingDate BETWEEN ? AND ? AND timerecording.PNR = ?
            GROUP BY RecordingWeek, RecordingYear
            HAVING Deviation <> 0;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../evaluationPerEmployee.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $evaluationFrom, $evaluationTo, $evaluatedPnr);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $deviationInSec=0;
    $sum=0;
    $numberOfValues=0;
    $average=0;
    $max=0;
    $min=0;
    $allDeviationsInSec = array();
    $standardDeviation =0;
    $checkResult = mysqli_num_rows($resultData);

    if($checkResult>0){
      while($row=mysqli_fetch_assoc($resultData)){
          $deviation =$row['Deviation'];
          $deviationInSec = deviationInSec($deviation);

          $sum = getSum($deviationInSec,$sum);

          if(getMin($deviationInSec, $min) ==  true){
            $min = abs((int)$deviationInSec);
          }

          if(getMax($deviationInSec, $max) ==  true){
            $max = abs((int)$deviationInSec);
          }

          array_push($allDeviationsInSec, $deviationInSec);
      }

      $numberOfValues = getWorkingDays($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr);
      $average = getAverage($sum, $numberOfValues);

      $standardDeviation= getStandardDeviation($allDeviationsInSec);
    }


    $resultWeeklyWorkingHoursPerEmployee = formatEvaluatedResults($sum, $average, $min, $max, $standardDeviation);

  return $resultWeeklyWorkingHoursPerEmployee;
}



//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung der Abweichungen
//der Kernarbeitzeiten eines Mitarbeiters über alle seine Projekte
//In der Funktion werden die Abfrageergebnisse für Abweichungen von Kernarbeitszeit-Von und für Abweichungen von Kernarbeitszeit-Bis zusammengeführt
function evaluateCoreWorkingTimePerEmployee($conn, $evaluationFrom, $evaluationTo,$evaluatedPnr){

    $sum = 0;
    $numberOfValues = 0;
    $average = 0;
    $max = 0;
    $min = 0;
    $standardDeviation = 0;


    $resultCoreWorkingHoursFrom = evaluateCoreWorkingTimeFromPerEmployee($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr);
    $resultCoreWorkingHoursTo = evaluateCoreWorkingTimeToPerEmployee($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr);
    $allDeviationsInSec =array_merge($resultCoreWorkingHoursFrom, $resultCoreWorkingHoursTo);


    if(count($allDeviationsInSec) > 0){

      foreach($allDeviationsInSec as $deviationInSec) {

            $sum = getSum($deviationInSec,$sum);

            if(getMin($deviationInSec, $min) ==  true){
            $min = abs((int)$deviationInSec);
            }

            if(getMax($deviationInSec, $max) ==  true){
            $max = abs((int)$deviationInSec);
            }
      }

      $numberOfValues = getWorkingDays($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr);
      $average = getAverage($sum, $numberOfValues);

      $standardDeviation= getStandardDeviation($allDeviationsInSec);
    }

    $resultCoreWorkingHoursPerProject = formatEvaluatedResults($sum,$average, $min, $max, $standardDeviation);

  return $resultCoreWorkingHoursPerProject;
}


//Ermitteln alle Abweichungen (in Sek) von Kernarbeitszeit-Von für einen Mitarbeiter
function evaluateCoreWorkingTimeFromPerEmployee($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr){

    $sql = "SELECT TIMEDIFF(CoreWorkingTimeFrom, TaskBegin) AS Deviation
            FROM timerecording, employee
            WHERE timerecording.pnr = employee.PNR
            AND TaskBegin < CoreWorkingTimeFrom
            AND RecordingDate BETWEEN ? AND ?
            AND timerecording.PNR = ? ;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../evaluationPerEmployee.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $evaluationFrom, $evaluationTo,$evaluatedPnr);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $allDeviationsInSecFrom = array();

    while($row=mysqli_fetch_assoc($resultData)){
      $deviation=$row['Deviation'];
      $deviationInSec = deviationInSec($deviation);
      array_push($allDeviationsInSecFrom, $deviationInSec);
    }

  return $allDeviationsInSecFrom;
}


//Ermitteln alle Abweichungen (in Sek) von Kernarbeitszeit-Bis für einen Mitarbeiter
function evaluateCoreWorkingTimeToPerEmployee($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr){

    $sql = "SELECT TIMEDIFF(TaskEnd, CoreWorkingTimeTo) AS Deviation
    FROM timerecording, employee
    WHERE timerecording.pnr = employee.PNR
    AND TaskEnd > CoreWorkingTimeTo
    AND RecordingDate BETWEEN ? AND ?
    AND timerecording.PNR = ?;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../evaluationPerEmployee.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $evaluationFrom, $evaluationTo, $evaluatedPnr);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $allDeviationsInSecTo = array();

    while($row=mysqli_fetch_assoc($resultData)){
      $deviation=$row['Deviation'];
      $deviationInSec = deviationInSec($deviation);

      array_push($allDeviationsInSecTo, $deviationInSec);
    }

  return $allDeviationsInSecTo;
}





//Funktionen für die Übersicht der Abweichungen eines Mitarbeites für die einzelnen Projekte


//Ermittelt die Projekte des Mitarbeiters
function getAllProjectIdsFromEmployee($conn, $evaluatedPnr){

    $sql = "SELECT ProjectID FROM `employeeproject` WHERE PNR = ? ORDER BY ProjectID;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../evaluationPerEmployee.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $evaluatedPnr);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $allProjectIds = array();

    while($row=mysqli_fetch_assoc($resultData)){
      array_push($allProjectIds, $row['ProjectID']);
    }

  return $allProjectIds;
}

//Überprüft an wie vielen Tagen der Mitarbeiter im angegebenen Zeitraum an dem Projekt gearbeitet hat
function getWorkingDaysPerProject($conn, $evaluationFrom, $evaluationTo, $projectId , $evaluatedPnr){

    $sql = "SELECT COUNT(DISTINCT RecordingDate) AS NumberOfWorkingDays
            FROM timerecording
            WHERE RecordingDate BETWEEN ? AND ?
            AND ProjectID = ?
            AND PNR = ? ;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../evaluationPerEmployee.php?error=stmtfailed");
        exit();

    }

    mysqli_stmt_bind_param($stmt, "ssss", $evaluationFrom, $evaluationTo, $projectId , $evaluatedPnr);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $row= mysqli_fetch_assoc($resultData);
    $numberOfWorkingDays = $row['NumberOfWorkingDays'];

  return $numberOfWorkingDays;
}



//Evaluiert die Summe, den Durchschnitt, das Minimum, das Maximum und die Standardabweichung
//der Wochenstundenabweichungen eines Mitarbeiters für ein einzelnes Projekt
function evaluateWeeklyWorkingsHoursPerProject($conn, $evaluationFrom, $evaluationTo,$projectId , $evaluatedPnr){

    $sql = "SELECT WEEKOFYEAR(RecordingDate) AS RecordingWeek,
            YEAR(RecordingDate) AS RecordingYear,
            TIMEDIFF((CAST(WeeklyWorkingHours*10000 AS TIME)), (CAST((SUM(TIMEDIFF(TaskEnd, TaskBegin)))AS TIME))) AS Deviation
            FROM timerecording, employee
            WHERE timerecording.PNR = employee.PNR
            AND RecordingDate BETWEEN ? AND ?
            AND ProjectID=?
            AND timerecording.PNR = ?
            GROUP BY RecordingWeek, RecordingYear
            HAVING Deviation <> 0;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../evaluationPerEmployee.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssss", $evaluationFrom, $evaluationTo, $projectId , $evaluatedPnr);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $sum=0;
    $numberOfValues=0;
    $average=0;
    $max=0;
    $min=0;
    $deviationInSec=0;
    $allDeviationsInSec = array();
    $standardDeviation =0;
    $checkResult = mysqli_num_rows($resultData);

    if($checkResult>0){

      while($row=mysqli_fetch_assoc($resultData)){
        $deviation =$row['Deviation'];
        $deviationInSec = deviationInSec($deviation);

        $sum = getSum($deviationInSec,$sum);

        if(getMin($deviationInSec, $min) ==  true){
          $min = abs((int)$deviationInSec);
        }

        if(getMax($deviationInSec, $max) ==  true){
          $max = abs((int)$deviationInSec);
        }

        array_push($allDeviationsInSec, $deviationInSec);
      }

      $numberOfValues = getWorkingDaysPerProject($conn, $evaluationFrom, $evaluationTo, $projectId , $evaluatedPnr);
      $average = getAverage($sum, $numberOfValues);

      $standardDeviation= getStandardDeviation($allDeviationsInSec);
    }

    $resultWeeklyWorkingHoursPerProject = formatEvaluatedResults($sum, $average, $min, $max, $standardDeviation);

  return $resultWeeklyWorkingHoursPerProject;
}

//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung der Abweichungen
//der Kernarbeitzeiten eines Mitarbeiters für ein einzelnes Projekt
//In der Funktion werden die Abfrageergebnisse für Abweichungen von Kernarbeitszeit-Von und für Abweichungen von Kernarbeitszeit-Bis zusammengeführt
function evaluateCoreWorkingTimePerProject($conn, $evaluationFrom, $evaluationTo,$projectId , $evaluatedPnr){

    $sum = 0;
    $numberOfValues = 0;
    $average = 0;
    $max = 0;
    $min = 0;
    $standardDeviation = 0;

    $resultCoreWorkingHoursFrom = evaluateCoreWorkingTimeFromPerProject($conn, $evaluationFrom, $evaluationTo, $projectId , $evaluatedPnr);
    $resultCoreWorkingHoursTo = evaluateCoreWorkingTimeToPerProject($conn, $evaluationFrom, $evaluationTo, $projectId , $evaluatedPnr);
    $allDeviationsInSec =array_merge($resultCoreWorkingHoursFrom, $resultCoreWorkingHoursTo);

    if(count($allDeviationsInSec) > 0){

      foreach($allDeviationsInSec as $deviationInSec) {

            $sum = getSum($deviationInSec,$sum);

            if(getMin($deviationInSec, $min) ==  true){
            $min = abs((int)$deviationInSec);
            }

            if(getMax($deviationInSec, $max) ==  true){
            $max = abs((int)$deviationInSec);
            }
        }

        $numberOfValues = getWorkingDaysPerProject($conn, $evaluationFrom, $evaluationTo, $projectId , $evaluatedPnr);
        $average = getAverage($sum, $numberOfValues);

        $standardDeviation= getStandardDeviation($allDeviationsInSec);
    }
    $resultCoreWorkingHoursPerProject = formatEvaluatedResults($sum,$average, $min, $max, $standardDeviation);
    
  return $resultCoreWorkingHoursPerProject;
}


//Ermitteln alle Abweichungen (in Sek) von Kernarbeitszeit-Von für einen Mitarbeiter für die einzelnen Projekte
function evaluateCoreWorkingTimeFromPerProject($conn, $evaluationFrom, $evaluationTo, $projectId , $evaluatedPnr){

    $sql = "SELECT TIMEDIFF(CoreWorkingTimeFrom, TaskBegin) AS Deviation
            FROM timerecording, employee
            WHERE timerecording.pnr = employee.PNR
            AND TaskBegin < CoreWorkingTimeFrom
            AND RecordingDate BETWEEN ? AND ?
            AND ProjectID = ?
            AND timerecording.PNR = ? ;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../evaluationPerEmployee.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssss", $evaluationFrom, $evaluationTo,$projectId , $evaluatedPnr);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $allDeviationsInSecFrom = array();

    while($row=mysqli_fetch_assoc($resultData)){
      $deviation=$row['Deviation'];
      $deviationInSec = deviationInSec($deviation);
      array_push($allDeviationsInSecFrom, $deviationInSec);
    }

  return $allDeviationsInSecFrom;
}


//Ermitteln alle Abweichungen (in Sek) von Kernarbeitszeit-Bis für einen Mitarbeiter für ein Projekt
function evaluateCoreWorkingTimeToPerProject($conn, $evaluationFrom, $evaluationTo, $projectId , $evaluatedPnr){

    $sql = "SELECT TIMEDIFF(TaskEnd, CoreWorkingTimeTo) AS Deviation
    FROM timerecording, employee
    WHERE timerecording.pnr = employee.PNR
    AND TaskEnd > CoreWorkingTimeTo
    AND RecordingDate BETWEEN ? AND ?
    AND ProjectID = ?
    AND timerecording.PNR = ?;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../evaluationPerEmployee.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssss", $evaluationFrom, $evaluationTo, $projectId , $evaluatedPnr);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $allDeviationsInSecTo = array();

    while($row=mysqli_fetch_assoc($resultData)){
      $deviation=$row['Deviation'];
      $deviationInSec = deviationInSec($deviation);

      array_push($allDeviationsInSecTo, $deviationInSec);
    }

  return $allDeviationsInSecTo;
}
