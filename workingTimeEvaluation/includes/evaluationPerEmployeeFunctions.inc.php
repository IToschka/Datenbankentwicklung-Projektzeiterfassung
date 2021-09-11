<?php

include_once 'calculationOfEvaluationDataFunctions.inc.php';




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

  $numberOfValues = getWorkingDays($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr);
  $average = getAverage($sum, $numberOfValues);

$resultWeeklyWorkingHoursPerEmployee = formatEvaluatedResults($sum, $average, $min, $max);
return $resultWeeklyWorkingHoursPerEmployee;
}



//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung der Abweichungen der gesamten Kernarbeitzeiten
//In der Funktion werden die Evaluationsergenisse für Abweichungen vor der Kernarbeitzeit und für Abweichungen nach der Kernarbeitzeit zusammengeführt
function evaluateCoreWorkingTimePerEmployee($conn, $evaluationFrom, $evaluationTo,$evaluatedPnr){

  $resultCoreWorkingHoursFrom = evaluateCoreWorkingTimeFromPerEmployee($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr);
  $resultCoreWorkingHoursTo = evaluateCoreWorkingTimeToPerEmployee($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr);

  $sum = $resultCoreWorkingHoursFrom["SumFrom"] + $resultCoreWorkingHoursTo["SumTo"];

  $numberOfValues = getWorkingDays($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr);
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

$resultCoreWorkingHoursPerEmployee = formatEvaluatedResults($sum,$average, $min, $max);
return $resultCoreWorkingHoursPerEmployee;

}


//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung
//für die Abweichungen, die vor der Kernarbeitszeit liegen

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


function invalidPnr($evaluatedPnr){
    $result;
    if(!preg_match("/[0-9]/", $evaluatedPnr)) {
            $result = true;
    }
    else{
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
