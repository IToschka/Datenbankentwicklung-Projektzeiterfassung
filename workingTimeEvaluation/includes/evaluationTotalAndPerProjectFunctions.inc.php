<?php
//Autor der Datei Tamara Romer
//Mit Ausnahme der Stored Functions GetAverageTotal und GetAveragePerProject: Autor Irena Toschka
include_once 'calculationOfEvaluationDataFunctions.inc.php';


//Fehlermeldungen
function invalidEvaluationDate($evaluationFrom, $evaluationTo){
    if($evaluationFrom>$evaluationTo) {
          return true;
    }
    else{
         return false;
        }
}


//Funktionen für die Gesamtübersicht der Abweichungen aller Mitarbeiter über alle Projekte


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

    //Von Irena Toschka
    $sql = "SELECT GetAverageTotal($sum) AS Average;";
    $resultData = mysqli_query($conn, $sql);
    $row= mysqli_fetch_assoc($resultData);
    $average = $row['Average'];

    $standardDeviation= getStandardDeviation($allDeviationsInSec);
    }

    $resultWeeklyWorkingHoursTotal = formatEvaluatedResults($sum, $average, $min, $max, $standardDeviation);

  return $resultWeeklyWorkingHoursTotal;
}



//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung der Abweichungen
//der Kernarbeitzeiten aller Mitarbeiter über alle Projekte
//In der Funktion werden die Abfrageergebnisse für Abweichungen von Kernarbeitszeit-Von und für Abweichungen von Kernarbeitszeit-Bis zusammengeführt
function evaluateCoreWorkingTimeTotal($conn, $evaluationFrom, $evaluationTo){

    $sum = 0;
    $numberOfValues = 0;
    $average = 0;
    $max = 0;
    $min = 0;
    $standardDeviation = 0;

    $resultCoreWorkingHoursFrom = evaluateCoreWorkingTimeFromTotal($conn, $evaluationFrom, $evaluationTo);
    $resultCoreWorkingHoursTo = evaluateCoreWorkingTimeToTotal($conn, $evaluationFrom, $evaluationTo);
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

      //Irena Toschka
      $sql = "SELECT GetAverageTotal($sum) AS Average;";
      $resultData = mysqli_query($conn, $sql);
      $row= mysqli_fetch_assoc($resultData);
      $average = $row['Average'];

      $standardDeviation= getStandardDeviation($allDeviationsInSec);
    }

    $resultCoreWorkingHoursTotal = formatEvaluatedResults($sum,$average, $min, $max, $standardDeviation);

  return $resultCoreWorkingHoursTotal;
}

//Ermitteln alle Abweichungen (in Sek) von Kernarbeitszeit-Von für alle Mitarbeiter und alle Projekte
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

    $allDeviationsInSecFrom = array();

    while($row=mysqli_fetch_assoc($resultData)){
      $deviation=$row['Deviation'];
      $deviationInSec = deviationInSec($deviation);
      array_push($allDeviationsInSecFrom, $deviationInSec);
    }

  return $allDeviationsInSecFrom;
  }


//Ermitteln alle Abweichungen (in Sek) von Kernarbeitszeit-Bis für alle Mitarbeiter und alle Projekte
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

    $allDeviationsInSecTo = array();

    while($row=mysqli_fetch_assoc($resultData)){
      $deviation=$row['Deviation'];
      $deviationInSec = deviationInSec($deviation);

      array_push($allDeviationsInSecTo, $deviationInSec);
    }

  return $allDeviationsInSecTo;
}









//Funktionen für die Übersicht der Abweichungen für die einzelnen Projekte


//Ermittelt alle Projekte, die im angegebenen Evaluierungszeitraum bereits gestartet sind
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

//Ermittelt wie viele Mitarbeiter in einem Projekt arbeiten, um Projekte ohne Mitarbeiter ausfindig zu machen
function getEmployeesPerProject($conn, $projectId){

    $sql = "SELECT COUNT(PNR) AS NumberOfEmployeesPerProject FROM employeeproject WHERE ProjectID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../evaluationTotalAndPerProject.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $projectId );
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $row= mysqli_fetch_assoc($resultData);
    $numberOfEmployeesPerProject = $row['NumberOfEmployeesPerProject'];

  return $numberOfEmployeesPerProject;
}

//Evaluiert die Summe, den Durchschnitt, das Minimum, das Maximum und die Standardabweichung
//der Wochenstundenabweichungen für die einzelnen Projekt
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

      //Irena Toschka
      $sql = "SELECT GetAveragePerProject($sum, $projectId) AS Average;";
      $resultData = mysqli_query($conn, $sql);
      $row= mysqli_fetch_assoc($resultData);
      $average = $row['Average'];

      $standardDeviation= getStandardDeviation($allDeviationsInSec);
    }

    $resultWeeklyWorkingHoursPerProject = formatEvaluatedResults($sum, $average, $min, $max, $standardDeviation);

  return $resultWeeklyWorkingHoursPerProject;
}

//Evaluiiert die Summe, den Durchschnitt, das Minimum, das Maxmimum und die Standardabweichung der Abweichungen
//der Kernarbeitzeiten für die einzelnen Projekte
//In der Funktion werden die Abfrageergebnisse für Abweichungen von Kernarbeitszeit-Von und für Abweichungen von Kernarbeitszeit-Bis zusammengeführt
function evaluateCoreWorkingTimePerProject($conn, $evaluationFrom, $evaluationTo,$projectId ){

    $sum = 0;
    $numberOfValues = 0;
    $average = 0;
    $max = 0;
    $min = 0;
    $standardDeviation = 0;

    $resultCoreWorkingHoursFrom = evaluateCoreWorkingTimeFromPerProject($conn, $evaluationFrom, $evaluationTo, $projectId );
    $resultCoreWorkingHoursTo = evaluateCoreWorkingTimeToPerProject($conn, $evaluationFrom, $evaluationTo, $projectId );
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

      //Irena Toschka
      $sql = "SELECT GetAveragePerProject($sum, $projectId) AS Average;";
      $resultData = mysqli_query($conn, $sql);
      $row= mysqli_fetch_assoc($resultData);
      $average = $row['Average'];

      $standardDeviation= getStandardDeviation($allDeviationsInSec);
    }

    $resultCoreWorkingHoursPerProject = formatEvaluatedResults($sum,$average, $min, $max, $standardDeviation);

  return $resultCoreWorkingHoursPerProject;
}


//Ermitteln alle Abweichungen (in Sek) von Kernarbeitszeit-Von für die einzelnen Projekt
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

    $allDeviationsInSecFrom = array();

    while($row=mysqli_fetch_assoc($resultData)){
      $deviation=$row['Deviation'];
      $deviationInSec = deviationInSec($deviation);
      array_push($allDeviationsInSecFrom, $deviationInSec);

    }

  return $allDeviationsInSecFrom;
}


//Ermitteln alle Abweichungen (in Sek) von Kernarbeitszeit-Bis für die einzelnen Projekt
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

    $allDeviationsInSecTo = array();

    while($row=mysqli_fetch_assoc($resultData)){
      $deviation=$row['Deviation'];
      $deviationInSec = deviationInSec($deviation);

      array_push($allDeviationsInSecTo, $deviationInSec);
    }

  return $allDeviationsInSecTo;
}
