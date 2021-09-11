<?php

include_once 'calculationOfEvaluationDataFunctions.inc.php';


function invalidEvaluationDate($evaluationFrom, $evaluationTo){
    $result;
    if($evaluationFrom>$evaluationTo) {
            $result = true;
    }
    else{
         $result = false;
        }
    return $result;
}


//Evaluiert die Summe, den Durchschnitt, das Minimum, das Maximum und die Standardabweichung
//der Wochenstundenabweichungen aller Mitarbeiter und aller Projekte
function evaluateWeeklyWorkingsHoursTotal($conn, $evaluationFrom, $evaluationTo){

  $sql = "SELECT timerecording.PNR, WEEKOFYEAR(RecordingDate) AS RecordingWeek,
          YEAR(RecordingDate) AS RecordingYear,
          TIMEDIFF((CAST(WeeklyWorkingHours*10000 AS TIME)), (CAST((SUM(TIMEDIFF(TaskEnd, TaskBegin)))AS TIME))) AS Deviation
          FROM timerecording, employee WHERE timerecording.PNR = employee.PNR
          AND RecordingDate BETWEEN ? AND ?
          GROUP BY RecordingWeek, RecordingYear, timerecording.PNR
          HAVING Deviation <> 0;";

  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../evaluationTotalAndPerProject.php?error=stmtfailed");
      exit();
  }

  mysqli_stmt_bind_param($stmt, "ss", $evaluationFrom, $evaluationTo);
  mysqli_stmt_execute($stmt);

  $resultData = mysqli_stmt_get_result($stmt);
  $sum=0;
  $numberOfValues=0;
  $average=0;
  $max=0;
  $min=0;
  $deviationInSec=0;

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

  }

$numberOfValues = getNumberOfEmployees($conn);
$average = getAverage($sum, $numberOfValues);

$resultWeeklyWorkingHoursTotal = formatEvaluatedResults($sum, $average, $min, $max);
return $resultWeeklyWorkingHoursTotal;
}



//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung der Abweichungen
//der gesamten Kernarbeitzeiten aller Mitarbeiter und aller Projekte
//In der Funktion werden die Evaluationsergenisse für Abweichungen vor der Kernarbeitzeit und für Abweichungen nach der Kernarbeitzeit zusammengeführt
function evaluateCoreWorkingTimeTotal($conn, $evaluationFrom, $evaluationTo){

  $resultCoreWorkingHoursFrom = evaluateCoreWorkingTimeFromTotal($conn, $evaluationFrom, $evaluationTo);
  $resultCoreWorkingHoursTo = evaluateCoreWorkingTimeToTotal($conn, $evaluationFrom, $evaluationTo);

  $sum = $resultCoreWorkingHoursFrom["SumFrom"] + $resultCoreWorkingHoursTo["SumTo"];

  $numberOfValues = getNumberOfEmployees($conn);
  $average = getAverage($sum, $numberOfValues);

  if($resultCoreWorkingHoursFrom["MinFrom"]< $resultCoreWorkingHoursTo["MinTo"]){
      $min=$resultCoreWorkingHoursFrom["MinFrom"];
  }else{
    $min=$resultCoreWorkingHoursTo["MinTo"];
  }


  if($resultCoreWorkingHoursFrom["MaxFrom"]>$resultCoreWorkingHoursTo["MaxTo"]){
    $max=$resultCoreWorkingHoursFrom["MaxFrom"];
  }else{
    $max=$resultCoreWorkingHoursTo["MaxTo"];
  }

$resultCoreWorkingHoursTotal = formatEvaluatedResults($sum,$average, $min, $max);
return $resultCoreWorkingHoursTotal;

}


//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung
//für die Abweichungen aller Mitarbeiter und Projekte, die vor der Kernarbeitszeit liegen

function evaluateCoreWorkingTimeFromTotal($conn, $evaluationFrom, $evaluationTo){

  $sql = "SELECT TIMEDIFF(CoreWorkingTimeFrom, TaskBegin) AS Deviation
          FROM timerecording, employee
          WHERE timerecording.pnr = employee.PNR
          AND TaskBegin < CoreWorkingTimeFrom
          AND RecordingDate BETWEEN ? AND ?;";

  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../evaluationTotalAndPerProject.php?error=stmtfailed");
      exit();
  }

  mysqli_stmt_bind_param($stmt, "ss", $evaluationFrom, $evaluationTo);
  mysqli_stmt_execute($stmt);

  $resultData = mysqli_stmt_get_result($stmt);
  $sum=0;
  $max=0;
  $min=0;

  while($row=mysqli_fetch_assoc($resultData)){
    $deviation=$row['Deviation'];
    $deviationInSec = deviationInSec($deviation);

    $sum = getSum($deviationInSec,$sum);

    if(getMin($deviationInSec, $min) ==  true){
      $min = $deviationInSec;
    }

    if(getMax($deviationInSec, $max) ==  true){
      $max = $deviationInSec;
    }

  }

$resultCoreWorkingHoursFrom = array("SumFrom"=>$sum, "MinFrom"=>$min, "MaxFrom"=>$max);
return $resultCoreWorkingHoursFrom;
}


//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung
//für die Abweichungen aller Mitarbeiter und Projekte die nach der Kernarbeitszeit liegen
function evaluateCoreWorkingTimeToTotal($conn, $evaluationFrom, $evaluationTo){

  $sql = "SELECT TIMEDIFF(TaskEnd, CoreWorkingTimeTo) AS Deviation
  FROM timerecording, employee
  WHERE timerecording.pnr = employee.PNR
  AND TaskEnd > CoreWorkingTimeTo
  AND RecordingDate BETWEEN ? AND ?;";

  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../evaluationTotalAndPerProject.php?error=stmtfailed");
      exit();
  }

  mysqli_stmt_bind_param($stmt, "ss", $evaluationFrom, $evaluationTo);
  mysqli_stmt_execute($stmt);

  $resultData = mysqli_stmt_get_result($stmt);
  $sum=0;
  $max=0;
  $min=0;

  while($row=mysqli_fetch_assoc($resultData)){
    $deviation=$row['Deviation'];
    $deviationInSec = deviationInSec($deviation);

    $sum = getSum($deviationInSec,$sum);

    if(getMin($deviationInSec, $min) ==  true){
      $min = $deviationInSec;
    }

    if(getMax($deviationInSec, $max) ==  true){
      $max = $deviationInSec;
    }

  }

$resultCoreWorkingHoursTo = array("SumTo"=>$sum, "MinTo"=>$min, "MaxTo"=>$max);
return $resultCoreWorkingHoursTo;
}


//Ermittelt für die Durchschnittsberechnung die Anzahl aller Mitarbeiter
//Mit der Ausnahme PNR = 0: Ein Admin sollte keine Arbeitszeitbuchung haben
function getNumberOfEmployees($conn){
  $sql = "SELECT COUNT(PNR) AS NumberOfEmployees FROM employee WHERE PNR<>0;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../evaluationTotalAndPerProject.php?error=stmtfailed");
      exit();

  }

  mysqli_stmt_execute($stmt);
  $resultData = mysqli_stmt_get_result($stmt);
  $row= mysqli_fetch_assoc($resultData);
  $numberOfEmployees = $row['NumberOfEmployees'];

  return $numberOfEmployees;

}










function getAllProjectIds($conn, $evaluationFrom){

  $sql = "SELECT ProjectId FROM project WHERE  BeginDate <= ? ORDER BY ProjectId;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../evaluationTotalAndPerProject.php?error=stmtfailed");
      exit();

  }

  mysqli_stmt_bind_param($stmt, "s", $evaluationFrom);

  mysqli_stmt_execute($stmt);
  $resultData = mysqli_stmt_get_result($stmt);
  $allProjectIds = array();

  while($row=mysqli_fetch_assoc($resultData)){
  array_push($allProjectIds, $row['ProjectId']);

  }
  return $allProjectIds;
}


function evaluateWeeklyWorkingsHoursPerProject($conn, $evaluationFrom, $evaluationTo,$element){

  $sql = "SELECT timerecording.PNR, WEEKOFYEAR(RecordingDate) AS RecordingWeek,
          YEAR(RecordingDate) AS RecordingYear,
          TIMEDIFF((CAST(WeeklyWorkingHours*10000 AS TIME)), (CAST((SUM(TIMEDIFF(TaskEnd, TaskBegin)))AS TIME))) AS Deviation
          FROM timerecording, employee
          WHERE timerecording.PNR = employee.PNR
          AND RecordingDate BETWEEN ? AND ?
          AND ProjectID=?
          GROUP BY RecordingWeek, RecordingYear, timerecording.PNR
          HAVING Deviation <> 0;";

  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../evaluationTotalAndPerProject.php?error=stmtfailed");
      exit();
  }

  mysqli_stmt_bind_param($stmt, "sss", $evaluationFrom, $evaluationTo, $element);
  mysqli_stmt_execute($stmt);

  $resultData = mysqli_stmt_get_result($stmt);
  $sum=0;
  $numberOfValues=0;
  $average=0;
  $max=0;
  $min=0;
  $deviationInSec=0;

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

  }

$numberOfValues = getEmployeesPerProject($conn, $element);
$average = getAverage($sum, $numberOfValues);

$resultWeeklyWorkingHoursPerProject = formatEvaluatedResults($sum, $average, $min, $max);
return $resultWeeklyWorkingHoursPerProject;
}


function getEmployeesPerProject($conn, $element){
  $sql = "SELECT COUNT(PNR) AS NumberOfEmployeesPerProject FROM employeeproject WHERE ProjectID = ?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../evaluationPerEmployee.php?error=stmtfailed");
      exit();

  }

  mysqli_stmt_bind_param($stmt, "s", $element);
  mysqli_stmt_execute($stmt);
  $resultData = mysqli_stmt_get_result($stmt);
  $row= mysqli_fetch_assoc($resultData);
  $numberOfEmployeesPerProject = $row['NumberOfEmployeesPerProject'];

  return $numberOfEmployeesPerProject;


}




function evaluateCoreWorkingTimePerProject($conn, $evaluationFrom, $evaluationTo,$element){

  $resultCoreWorkingHoursFrom = evaluateCoreWorkingTimeFromPerProject($conn, $evaluationFrom, $evaluationTo, $element);
  $resultCoreWorkingHoursTo = evaluateCoreWorkingTimeToPerProject($conn, $evaluationFrom, $evaluationTo, $element);

  $sum = $resultCoreWorkingHoursFrom["SumFrom"] + $resultCoreWorkingHoursTo["SumTo"];

  $numberOfValues = getEmployeesPerProject($conn, $element);
  $average = getAverage($sum, $numberOfValues);

  if($resultCoreWorkingHoursFrom["MinFrom"]< $resultCoreWorkingHoursTo["MinTo"]){
      $min=$resultCoreWorkingHoursFrom["MinFrom"];
  }else{
    $min=$resultCoreWorkingHoursTo["MinTo"];
  }


  if($resultCoreWorkingHoursFrom["MaxFrom"]>$resultCoreWorkingHoursTo["MaxTo"]){
    $max=$resultCoreWorkingHoursFrom["MaxFrom"];
  }else{
    $max=$resultCoreWorkingHoursTo["MaxTo"];
  }

$resultCoreWorkingHoursPerProject = formatEvaluatedResults($sum,$average, $min, $max);
return $resultCoreWorkingHoursPerProject;

}


//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung
//für die Abweichungen, die vor der Kernarbeitszeit liegen

function evaluateCoreWorkingTimeFromPerProject($conn, $evaluationFrom, $evaluationTo, $element){

  $sql = "SELECT TIMEDIFF(CoreWorkingTimeFrom, TaskBegin) AS Deviation
          FROM timerecording, employee
          WHERE timerecording.pnr = employee.PNR
          AND TaskBegin < CoreWorkingTimeFrom
          AND RecordingDate BETWEEN ? AND ?
          AND ProjectID = ? ;";

  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../evaluationTotalAndPerProject.php?error=stmtfailed");
      exit();
  }

  mysqli_stmt_bind_param($stmt, "sss", $evaluationFrom, $evaluationTo,$element);
  mysqli_stmt_execute($stmt);

  $resultData = mysqli_stmt_get_result($stmt);
  $sum=0;
  $max=0;
  $min=0;

  while($row=mysqli_fetch_assoc($resultData)){
    $deviation=$row['Deviation'];
    $deviationInSec = deviationInSec($deviation);

    $sum = getSum($deviationInSec,$sum);

    if(getMin($deviationInSec, $min) ==  true){
      $min = $deviationInSec;
    }

    if(getMax($deviationInSec, $max) ==  true){
      $max = $deviationInSec;
    }

  }

$resultCoreWorkingHoursFrom = array("SumFrom"=>$sum, "MinFrom"=>$min, "MaxFrom"=>$max);
return $resultCoreWorkingHoursFrom;
}


//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung
//für die Abweichungen die nach der Kernarbeitszeit liegen
function evaluateCoreWorkingTimeToPerProject($conn, $evaluationFrom, $evaluationTo, $element){

  $sql = "SELECT TIMEDIFF(TaskEnd, CoreWorkingTimeTo) AS Deviation
  FROM timerecording, employee
  WHERE timerecording.pnr = employee.PNR
  AND TaskEnd > CoreWorkingTimeTo
  AND RecordingDate BETWEEN ? AND ?
  AND ProjectID = ?;";

  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../evaluationTotalAndPerProject.php?error=stmtfailed");
      exit();
  }

  mysqli_stmt_bind_param($stmt, "sss", $evaluationFrom, $evaluationTo, $element);
  mysqli_stmt_execute($stmt);
  $resultData = mysqli_stmt_get_result($stmt);

  $sum=0;
  $max=0;
  $min=0;

  while($row=mysqli_fetch_assoc($resultData)){
    $deviation=$row['Deviation'];
    $deviationInSec = deviationInSec($deviation);

    $sum = getSum($deviationInSec,$sum);

    if(getMin($deviationInSec, $min) ==  true){
      $min = $deviationInSec;
    }

    if(getMax($deviationInSec, $max) ==  true){
      $max = $deviationInSec;
    }

  }

$resultCoreWorkingHoursTo = array("SumTo"=>$sum, "MinTo"=>$min, "MaxTo"=>$max);
return $resultCoreWorkingHoursTo;
}
