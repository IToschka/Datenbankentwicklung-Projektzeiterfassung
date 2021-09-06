<?php
include_once '../includes/dbh.inc.php';
include_once 'evaluationFunctions.php';


if (isset($_POST['button_Evaluate'])){

  $evaluationFrom= $_POST['evaluationFrom'];
  $evaluationTo = $_POST['evaluationTo'];
  $totalSumWeeklyWorkingHours=0;
  $totalAverageWeeklyWorkingHours=0;
  $totalMaxWeeklyWorkingHours=0;
  $totalMinWeeklyWorkingHours=0;



  $resultWeeklyWorkingHours= evaluateWeeklyWorkingsHoursTotal($conn, $evaluationFrom, $evaluationTo);
  $totalSumWeeklyWorkingHours= $resultWeeklyWorkingHours[0];
  $totalMaxWeeklyWorkingHours= $resultWeeklyWorkingHours[1];
  $totalMinWeeklyWorkingHours= $resultWeeklyWorkingHours[2];

}
