<?php

function getSum($deviation,$sum){
  $temp= explode(":", $deviation);
  $sum+= (int) $temp[0] * 3600;
  $sum+= (int) $temp[1] * 60;
  $sum+= (int) $temp[2];
  return $sum;
}


function getMax($deviation, $max){
  if($deviation > $max){
    return true;
  }else{
    return false;
  }
}

function getMin($deviation, $min){
  if($deviation < $min || $min == 0){
    return true;
  }else{
    return false;
  }
}

function evaluateWeeklyWorkingsHoursTotal($conn, $evaluationFrom, $evaluationTo){


  $sql = "SELECT timerecording.PNR, WEEKOFYEAR(RecordingDate) AS RecordingWeek,
          YEAR(RecordingDate) AS RecordingYear,
          TIMEDIFF((CAST(WeeklyWorkingHours*10000 AS TIME)), (CAST((SUM(TIMEDIFF(TaskEnd, TaskBegin)))AS TIME))) AS Deviation
          FROM timerecording, employee WHERE timerecording.PNR = employee.PNR
          AND RecordingDate BETWEEN ? AND ?
          GROUP BY RecordingWeek, RecordingYear, timerecording.PNR;";

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

    $sum = getSum($deviation,$sum);

    if(getMax($deviation, $max) ==  true){
      $max = $deviation;
    }

    if(getMin($deviation, $min) ==  true){
      $min = $deviation;
    }


  }

$formattedSumTotal = sprintf('%02d:%02d:%02d',
                ($sum/ 3600),
                ($sum / 60 % 60),
                $sum % 60);

$resultWeeklyWorkingHours = array($formattedSumTotal, $max, $min);
//echo $formattedSumTotal. "<br>";
//echo $min;
return $resultWeeklyWorkingHours;
}
