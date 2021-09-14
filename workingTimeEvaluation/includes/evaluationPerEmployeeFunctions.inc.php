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



//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung der Abweichungen der gesamten Kernarbeitzeiten
//In der Funktion werden die Evaluationsergenisse für Abweichungen vor der Kernarbeitzeit und für Abweichungen nach der Kernarbeitzeit zusammengeführt
function evaluateCoreWorkingTimePerEmployee($conn, $evaluationFrom, $evaluationTo,$evaluatedPnr){

  $resultCoreWorkingHoursFrom = evaluateCoreWorkingTimeFromPerEmployee($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr);
  $resultCoreWorkingHoursTo = evaluateCoreWorkingTimeToPerEmployee($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr);

  $sum = $resultCoreWorkingHoursFrom["SumFrom"] + $resultCoreWorkingHoursTo["SumTo"];

  $numberOfValues = getWorkingDays($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr);
  $average = getAverage($sum, $numberOfValues);

  if($resultCoreWorkingHoursFrom["MinFrom"] == 0 && $resultCoreWorkingHoursTo["MinTo"] == 0){
    $min = 0;
  }elseif($resultCoreWorkingHoursFrom["MinFrom"] != 0 && $resultCoreWorkingHoursTo["MinTo"] == 0){
    $min=$resultCoreWorkingHoursFrom["MinFrom"];
  }elseif($resultCoreWorkingHoursFrom["MinFrom"] == 0 && $resultCoreWorkingHoursTo["MinTo"] != 0){
    $min=$resultCoreWorkingHoursTo["MinTo"];
  }else{
    if($resultCoreWorkingHoursFrom["MinFrom"] <= $resultCoreWorkingHoursTo["MinTo"]){
      $min=$resultCoreWorkingHoursFrom["MinFrom"];
    }else{
      $min=$resultCoreWorkingHoursTo["MinTo"];
    }
  }

  if($resultCoreWorkingHoursFrom["MaxFrom"]>$resultCoreWorkingHoursTo["MaxTo"]){
    $max=$resultCoreWorkingHoursFrom["MaxFrom"];
  }else{
    $max=$resultCoreWorkingHoursTo["MaxTo"];
  }

  $allDeviationsInSec = array();
  $allDeviationsInSec = array_merge($resultCoreWorkingHoursFrom["AllDeviationsInSecFrom"], $resultCoreWorkingHoursTo["AllDeviationsInSecTo"]);
  if(!empty($allDeviationsInSec)){
      $standardDeviation = getStandardDeviation($allDeviationsInSec);
  }else{
      $standardDeviation = 0;
  }

$resultCoreWorkingHoursPerEmployee = formatEvaluatedResults($sum,$average, $min, $max, $standardDeviation);
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
  $allDeviationsInSec = array();
  $checkResult = mysqli_num_rows($resultData);

  if($checkResult>0){
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
      array_push($allDeviationsInSec, $deviationInSec);
    }
  }

$resultCoreWorkingHoursFrom = array("SumFrom"=>$sum, "MinFrom"=>$min, "MaxFrom"=>$max, "AllDeviationsInSecFrom"=>$allDeviationsInSec);
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
  $allDeviationsInSec = array();
  $checkResult = mysqli_num_rows($resultData);

  if($checkResult>0){

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

    array_push($allDeviationsInSec, $deviationInSec);

  }
}
$resultCoreWorkingHoursTo = array("SumTo"=>$sum, "MinTo"=>$min, "MaxTo"=>$max, "AllDeviationsInSecTo"=>$allDeviationsInSec);
return $resultCoreWorkingHoursTo;
}






















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






function evaluateCoreWorkingTimePerProject($conn, $evaluationFrom, $evaluationTo,$projectId , $evaluatedPnr){

  $resultCoreWorkingHoursFrom = evaluateCoreWorkingTimeFromPerProject($conn, $evaluationFrom, $evaluationTo, $projectId , $evaluatedPnr);
  $resultCoreWorkingHoursTo = evaluateCoreWorkingTimeToPerProject($conn, $evaluationFrom, $evaluationTo, $projectId , $evaluatedPnr);

  $sum = $resultCoreWorkingHoursFrom["SumFrom"] + $resultCoreWorkingHoursTo["SumTo"];

  $numberOfValues = getWorkingDaysPerProject($conn, $evaluationFrom, $evaluationTo, $projectId , $evaluatedPnr);
  $average = getAverage($sum, $numberOfValues);

  if($resultCoreWorkingHoursFrom["MinFrom"] == 0 && $resultCoreWorkingHoursTo["MinTo"] == 0){
    $min = 0;
  }elseif($resultCoreWorkingHoursFrom["MinFrom"] != 0 && $resultCoreWorkingHoursTo["MinTo"] == 0){
    $min=$resultCoreWorkingHoursFrom["MinFrom"];
  }elseif($resultCoreWorkingHoursFrom["MinFrom"] == 0 && $resultCoreWorkingHoursTo["MinTo"] != 0){
    $min=$resultCoreWorkingHoursTo["MinTo"];
  }else{
    if($resultCoreWorkingHoursFrom["MinFrom"] <= $resultCoreWorkingHoursTo["MinTo"]){
      $min=$resultCoreWorkingHoursFrom["MinFrom"];
    }else{
      $min=$resultCoreWorkingHoursTo["MinTo"];
    }
  }


  if($resultCoreWorkingHoursFrom["MaxFrom"]>$resultCoreWorkingHoursTo["MaxTo"]){
    $max=$resultCoreWorkingHoursFrom["MaxFrom"];
  }else{
    $max=$resultCoreWorkingHoursTo["MaxTo"];
  }

  $allDeviationsInSec = array();
  $allDeviationsInSec = array_merge($resultCoreWorkingHoursFrom["AllDeviationsInSecFrom"], $resultCoreWorkingHoursTo["AllDeviationsInSecTo"]);

  if(!empty($allDeviationsInSec)){

      $standardDeviation= getStandardDeviation($allDeviationsInSec);
  }else{
      $standardDeviation = 0;
  }

$resultCoreWorkingHoursPerProject = formatEvaluatedResults($sum,$average, $min, $max, $standardDeviation);
return $resultCoreWorkingHoursPerProject;

}


//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung
//für die Abweichungen, die vor der Kernarbeitszeit liegen

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
  $sum=0;
  $max=0;
  $min=0;
  $allDeviationsInSec = array();
  $checkResult = mysqli_num_rows($resultData);

  if($checkResult>0){

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

    array_push($allDeviationsInSec, $deviationInSec);

  }

}

$resultCoreWorkingHoursFrom = array("SumFrom"=>$sum, "MinFrom"=>$min, "MaxFrom"=>$max, "AllDeviationsInSecFrom"=>$allDeviationsInSec);
return $resultCoreWorkingHoursFrom;
}


//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung
//für die Abweichungen die nach der Kernarbeitszeit liegen
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

  $sum=0;
  $max=0;
  $min=0;
  $allDeviationsInSec = array();
  $checkResult = mysqli_num_rows($resultData);
  if($checkResult>0){

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
    array_push($allDeviationsInSec, $deviationInSec);

    }
}
$resultCoreWorkingHoursTo = array("SumTo"=>$sum, "MinTo"=>$min, "MaxTo"=>$max, "AllDeviationsInSecTo"=>$allDeviationsInSec);
return $resultCoreWorkingHoursTo;
}
