<?php

function deviationInSec($deviation){
  $temp= explode(":", $deviation);
  $deviationInSec = 0;
  $deviationInSec+= (int) $temp[0] * 3600;
  $deviationInSec+= (int) $temp[1] * 60;
  $deviationInSec+= (int) $temp[2];
  return $deviationInSec;
}

//Ermittelt die Summe der Abweichungen in Sekunden
function getSum($deviationInSec,$sum){
  $sum+= (int) $deviationInSec;
  return $sum;
}

//Ermittelt das Mimimum der Abweichungen in Sekunden
function getMin($deviationInSec, $min){

  if(abs((int)$deviationInSec) < $min || $min == 0){
    return true;
  }else{
    return false;
  }
}

//Ermittelt das Maximum der Abweichungen in Sekunden
function getMax($deviationInSec, $max){
  if(abs((int)$deviationInSec) > $max){
    return true;
  }else{
    return false;
  }
}

function formatEvaluatedResults($sum, $min, $max){
  $formattedSum= sprintf('%02d:%02d:%02d',
                    ($sum/ 3600),
                    ($sum / 60 % 60),
                    $sum % 60);

  $formattedMin= sprintf('%02d:%02d:%02d',
                    ($min/ 3600),
                    ($min / 60 % 60),
                    $min % 60);

  $formattedMax= sprintf('%02d:%02d:%02d',
                    ($max/ 3600),
                    ($max / 60 % 60),
                    $max % 60);

  $formattedResults = array("Sum"=>$formattedSum, "Min"=>$formattedMin, "Max"=>$formattedMax);
  return $formattedResults;
}


//Evaluiert die Summe, den Durchschnitt, das Minimum, das Maximum und die Standardabweichung aller Mitarbeiter und aller Projekte
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
      header("location: ../evaluationTotal.php?error=stmtfailed");
      exit();
  }

  mysqli_stmt_bind_param($stmt, "ss", $evaluationFrom, $evaluationTo);
  mysqli_stmt_execute($stmt);

  $resultData = mysqli_stmt_get_result($stmt);
  $sum=0;
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

$resultWeeklyWorkingHoursTotal = formatEvaluatedResults($sum, $min, $max);
return $resultWeeklyWorkingHoursTotal;
}



//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung der Abweichungen der gesamten Kernarbeitzeiten
//In der Funktion werden die Evaluationsergenisse für Abweichungen vor der Kernarbeitzeit und für Abweichungen nach der Kernarbeitzeit zusammengeführt
function evaluateCoreWorkingTimeTotal($conn, $evaluationFrom, $evaluationTo){

  $resultCoreWorkingHoursFrom = evaluateCoreWorkingTimeFromTotal($conn, $evaluationFrom, $evaluationTo);
  $resultCoreWorkingHoursTo = evaluateCoreWorkingTimeToTotal($conn, $evaluationFrom, $evaluationTo);

  $sum = $resultCoreWorkingHoursFrom["SumFrom"] + $resultCoreWorkingHoursTo["SumTo"];


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

$resultCoreWorkingHoursTotal = formatEvaluatedResults($sum, $min, $max);
return $resultCoreWorkingHoursTotal;

}


//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung
//für die Abweichungen, die vor der Kernarbeitszeit liegen

function evaluateCoreWorkingTimeFromTotal($conn, $evaluationFrom, $evaluationTo){

  $sql = "SELECT TIMEDIFF(CoreWorkingTimeFrom, TaskBegin) AS Deviation
          FROM timerecording, employee
          WHERE timerecording.pnr = employee.PNR
          AND TaskBegin < CoreWorkingTimeFrom
          AND RecordingDate BETWEEN ? AND ?;";

  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../evaluationTotal.php?error=stmtfailed");
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
//für die Abweichungen die nach der Kernarbeitszeit liegen
function evaluateCoreWorkingTimeToTotal($conn, $evaluationFrom, $evaluationTo){

  $sql = "SELECT TIMEDIFF(TaskEnd, CoreWorkingTimeTo) AS Deviation
  FROM timerecording, employee
  WHERE timerecording.pnr = employee.PNR
  AND TaskEnd > CoreWorkingTimeTo
  AND RecordingDate BETWEEN ? AND ?;";

  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../evaluationTotal.php?error=stmtfailed");
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
