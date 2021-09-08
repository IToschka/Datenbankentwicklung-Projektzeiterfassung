<?php
    include_once '../menu/workingTimeEvaluationMenu.php';
    include_once '../includes/dbh.inc.php';
    include_once 'evaluationFunctions.php'
?>
<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <link rel="stylesheet" href="../css/style.css">
       <title></title>
    </head>

    <body>

      <br>
      <br>
    <center>

      <?php

      $totalSumWeeklyWorkingHours = 0;
      $totalAverageWeeklyWorkingHours = 0;
      $totalMaxWeeklyWorkingHours = 0;
      $totalMinWeeklyWorkingHours = 0;



      $totalSumCoreWorkingHours = 0;
      $totalAverageCoreWorkingHours = 0;
      $totalMinCoreWorkingHours = 0;
      $totalMaxCoreWorkingHours = 0;


      if (isset($_POST['button_Evaluate'])){

        $evaluationFrom= $_POST['evaluationFrom'];
        $evaluationTo = $_POST['evaluationTo'];
        $resultWeeklyWorkingHoursTotal = evaluateWeeklyWorkingsHoursTotal($conn, $evaluationFrom, $evaluationTo);
        $totalSumWeeklyWorkingHours = $resultWeeklyWorkingHoursTotal["Sum"];
        $totalAverageWeeklyWorkingHours = $resultWeeklyWorkingHoursTotal["Average"];
        $totalMinWeeklyWorkingHours = $resultWeeklyWorkingHoursTotal["Min"];
        $totalMaxWeeklyWorkingHours = $resultWeeklyWorkingHoursTotal["Max"];




        $resultCoreWorkingHoursTotal = evaluateCoreWorkingTimeTotal($conn, $evaluationFrom, $evaluationTo);
        $totalSumCoreWorkingHours = $resultCoreWorkingHoursTotal["Sum"];
        $totalAverageCoreWorkingHours = $resultCoreWorkingHoursTotal["Average"];
        $totalMinCoreWorkingHours = $resultCoreWorkingHoursTotal["Min"];
        $totalMaxCoreWorkingHours = $resultCoreWorkingHoursTotal["Max"];
      }
       ?>

    <form action="evaluationTotalAndPerProject.php" method="POST" >
      Von: <input type="date" name="evaluationFrom" value= '<?php
      echo $evaluationFrom; ?>' required>
      Bis: <input type="date" name="evaluationTo"  value= '<?php
      echo $evaluationTo; ?>' required>

    <br>
    <br>
    <h3>Abweichung der Wochenarbeitsstunden</h3>

    <table>
        <tbody>
                <tr>
                    <td>Summe:</td>
                    <td>
                      <input type="text" textarea readonly="readonly" name="totalSumWeeklyWorkingHours"
                      value= '<?php
                      echo $totalSumWeeklyWorkingHours; ?>'>
                    </td>
                </tr>
                <tr>
                    <td>Durchschnitt</td>
                    <td><input type="text" textarea readonly="readonly" name="totalAverageWeeklyWorkingHours"
                    value= '<?php
                    echo $totalAverageWeeklyWorkingHours; ?>'</td>
                </tr>
                <tr>
                    <td>Minimum:</td>
                    <td>
                      <input type="text" textarea readonly="readonly" name="totalMinWeeklyWorkingHours"
                      value= '<?php
                      echo $totalMinWeeklyWorkingHours; ?>'></td>
                </tr>
                <tr>
                    <td>Maxmimum:</td>
                    <td>
                      <input type="text" textarea readonly="readonly" name="totalMaxWeeklyWorkingHours"
                      value= '<?php
                      echo $totalMaxWeeklyWorkingHours; ?>'></td>
                </tr>
                <tr>
                    <td>Standardabweichung:</td>
                    <td></td>
                </tr>
          </tbody>
      </table>
      <br>
      <br>
      <h3>Abweichung der Kernarbeitszeit</h3>
      <table>
          <tbody>
                  <tr>
                      <td>Summe:</td>
                      <td><input type="text" textarea readonly="readonly" name="totalSumCoreWorkingHours"
                      value= '<?php
                      echo $totalSumCoreWorkingHours; ?>'></td>
                  </tr>
                  <tr>
                      <td>Durchschnitt</td>
                      <td><input type="text" textarea readonly="readonly" name="totalAverageCoreWorkingHours"
                      value= '<?php
                      echo $totalAverageCoreWorkingHours; ?>'></td>
                  </tr>
                  <tr>
                      <td>Minimum:</td>
                      <td><input type="text" textarea readonly="readonly" name="totalMinCoreWorkingHours"
                      value= '<?php
                      echo $totalMinCoreWorkingHours; ?>'></td>
                  </tr>
                  <tr>
                      <td>Maxmimum:</td>
                      <td><input type="text" textarea readonly="readonly" name="totalMaxCoreWorkingHours"
                      value= '<?php
                      echo  $totalMaxCoreWorkingHours; ?>'></td>
                  </tr>
                  <tr>
                      <td>Standardabweichung:</td>
                      <td></td>
                  </tr>
            </tbody>
        </table>


        <input type="submit" name="button_EmployeeMenu" value="Zurück zum Menü">
        <input type="submit" name="button_Evaluate" value = "Auswerten">


    </form>
    <br>
    <br>


</center>
    </body>
</html>
