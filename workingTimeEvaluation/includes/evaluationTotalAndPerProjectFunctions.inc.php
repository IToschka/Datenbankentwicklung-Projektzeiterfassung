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
  $deviationInSec = 0;
  $sum = 0;
  $numberOfValues = 0;
  $average = 0;
  $max = 0;
  $min = 0;
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


$sql = "SELECT GetAverageTotal($sum) AS Average;";
$result = mysqli_query($conn, $sql);
$row= mysqli_fetch_assoc($result);
$average = $row['Average'];

$standardDeviation= getStandardDeviationMax($allDeviationsInSec);
}

$resultWeeklyWorkingHoursTotal = formatEvaluatedResults($sum, $average, $min, $max, $standardDeviation);
return $resultWeeklyWorkingHoursTotal;
}



//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung der Abweichungen
//der gesamten Kernarbeitzeiten aller Mitarbeiter und aller Projekte
//In der Funktion werden die Evaluationsergenisse für Abweichungen vor der Kernarbeitzeit und für Abweichungen nach der Kernarbeitzeit zusammengeführt
function evaluateCoreWorkingTimeTotal($conn, $evaluationFrom, $evaluationTo){


  $resultCoreWorkingHoursFrom = evaluateCoreWorkingTimeFromTotal($conn, $evaluationFrom, $evaluationTo);
  $resultCoreWorkingHoursTo = evaluateCoreWorkingTimeToTotal($conn, $evaluationFrom, $evaluationTo);

  $sum = $resultCoreWorkingHoursFrom["SumFrom"] + $resultCoreWorkingHoursTo["SumTo"];

  $sql = "SELECT GetAverageTotal($sum) AS Average;";
  $result = mysqli_query($conn, $sql);
  $row= mysqli_fetch_assoc($result);
  $average = $row['Average'];

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

  $allDeviationsInSec = array();
  $allDeviationsInSec = array_merge($resultCoreWorkingHoursFrom["AllDeviationsInSecFrom"], $resultCoreWorkingHoursTo["AllDeviationsInSecTo"]);
  $standardDeviation = getStandardDeviationMax($allDeviationsInSec);


$resultCoreWorkingHoursTotal = formatEvaluatedResults($sum,$average, $min, $max, $standardDeviation);
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

function getEmployeesPerProject($conn, $projectId){
  $sql = "SELECT COUNT(PNR) AS NumberOfEmployeesPerProject FROM employeeproject WHERE ProjectID = ?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sql)){
      header("location: ../evaluationPerEmployee.php?error=stmtfailed");
      exit();

  }

  mysqli_stmt_bind_param($stmt, "s", $projectId );
  mysqli_stmt_execute($stmt);
  $resultData = mysqli_stmt_get_result($stmt);
  $row= mysqli_fetch_assoc($resultData);
  $numberOfEmployeesPerProject = $row['NumberOfEmployeesPerProject'];

  return $numberOfEmployeesPerProject;


}


function evaluateWeeklyWorkingsHoursPerProject($conn, $evaluationFrom, $evaluationTo,$projectId ){

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

  mysqli_stmt_bind_param($stmt, "sss", $evaluationFrom, $evaluationTo, $projectId );
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

$sql = "SELECT GetAveragePerProject($sum, $projectId) AS Average;";
$result = mysqli_query($conn, $sql);
$row= mysqli_fetch_assoc($result);
$average = $row['Average'];

$standardDeviation= getStandardDeviationMax($allDeviationsInSec);
}

$resultWeeklyWorkingHoursPerProject = formatEvaluatedResults($sum, $average, $min, $max, $standardDeviation);
return $resultWeeklyWorkingHoursPerProject;
}



function evaluateCoreWorkingTimePerProject($conn, $evaluationFrom, $evaluationTo,$projectId ){

  $resultCoreWorkingHoursFrom = evaluateCoreWorkingTimeFromPerProject($conn, $evaluationFrom, $evaluationTo, $projectId );
  $resultCoreWorkingHoursTo = evaluateCoreWorkingTimeToPerProject($conn, $evaluationFrom, $evaluationTo, $projectId );

  $sum = $resultCoreWorkingHoursFrom["SumFrom"] + $resultCoreWorkingHoursTo["SumTo"];

  $sql = "SELECT GetAveragePerProject($sum, $projectId) AS Average;";
  $result = mysqli_query($conn, $sql);
  $row= mysqli_fetch_assoc($result);
  $average = $row['Average'];

  if($resultCoreWorkingHoursFrom["MinFrom"] == 0 && $resultCoreWorkingHoursTo["MinTo"] ==0){
    $min = 0;
  }
  elseif($resultCoreWorkingHoursFrom["MinFrom"] <= $resultCoreWorkingHoursTo["MinTo"] && $resultCoreWorkingHoursFrom["MinFrom"] !=0){
      $min=$resultCoreWorkingHoursFrom["MinFrom"];
  }
  else{
    $min=$resultCoreWorkingHoursTo["MinTo"];
  }


  if($resultCoreWorkingHoursFrom["MaxFrom"]>$resultCoreWorkingHoursTo["MaxTo"]){
    $max=$resultCoreWorkingHoursFrom["MaxFrom"];
  }else{
    $max=$resultCoreWorkingHoursTo["MaxTo"];
  }

  $allDeviationsInSec = array();
  $allDeviationsInSec = array_merge($resultCoreWorkingHoursFrom["AllDeviationsInSecFrom"], $resultCoreWorkingHoursTo["AllDeviationsInSecTo"]);
  $standardDeviation = getStandardDeviationMax($allDeviationsInSec);


$resultCoreWorkingHoursPerProject = formatEvaluatedResults($sum,$average, $min, $max, $standardDeviation);
return $resultCoreWorkingHoursPerProject;

}


//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung
//für die Abweichungen, die vor der Kernarbeitszeit liegen
function evaluateCoreWorkingTimeFromPerProject($conn, $evaluationFrom, $evaluationTo, $projectId){

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

  mysqli_stmt_bind_param($stmt, "sss", $evaluationFrom, $evaluationTo,$projectId );
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
function evaluateCoreWorkingTimeToPerProject($conn, $evaluationFrom, $evaluationTo, $projectId){

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

  mysqli_stmt_bind_param($stmt, "sss", $evaluationFrom, $evaluationTo, $projectId );
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
