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
      $evaluatedPnr ="";

      $perEmployeeSumWeeklyWorkingHours = 0;
      $perEmployeeAverageWeeklyWorkingHours = 0;
      $perEmployeeMinWeeklyWorkingHours = 0;
      $perEmployeeMaxWeeklyWorkingHours = 0;
      $perEmployeeWeeklyWorkingHours = 0;



      $perEmployeeSumCoreWorkingHours = 0;
      $perEmployeeAverageCoreWorkingHours = 0;
      $perEmployeeMinCoreWorkingHours = 0;
      $perEmployeeMaxCoreWorkingHours = 0;


      if (isset($_POST['button_Evaluate'])){

        $evaluationFrom= $_POST['evaluationFrom'];
        $evaluationTo = $_POST['evaluationTo'];
        $evaluatedPnr = $_POST['evaluatedPnr'];


        $resultWeeklyWorkingHoursPerEmployee = evaluateWeeklyWorkingsHoursPerEmployee($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr);
        $perEmployeeSumWeeklyWorkingHours = $resultWeeklyWorkingHoursPerEmployee["Sum"];
        $perEmployeeAverageWeeklyWorkingHours = $resultWeeklyWorkingHoursPerEmployee["Average"];
        $perEmployeeMinWeeklyWorkingHours = $resultWeeklyWorkingHoursPerEmployee["Min"];
        $perEmployeeMaxWeeklyWorkingHours = $resultWeeklyWorkingHoursPerEmployee["Max"];




        $resultCoreWorkingHoursPerEmployee = evaluateCoreWorkingTimePerEmployee($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr);
        $perEmployeeSumCoreWorkingHours = $resultCoreWorkingHoursPerEmployee["Sum"];
        $perEmployeeAverageCoreWorkingHours = $resultCoreWorkingHoursPerEmployee["Average"];
        $perEmployeeMinCoreWorkingHours = $resultCoreWorkingHoursPerEmployee["Min"];
        $perEmployeeMaxCoreWorkingHours = $resultCoreWorkingHoursPerEmployee["Max"];
      }
       ?>

    <form action="evaluationPerEmployee.php" method="POST" >
      Von: <input type="date" name="evaluationFrom" value= '<?php
      echo $evaluationFrom; ?>' required>
      Bis: <input type="date" name="evaluationTo"  value= '<?php
      echo $evaluationTo; ?>' required> <br>
      Personalnummer: <input type="text" name="evaluatedPnr" value= '<?php
      echo $evaluatedPnr; ?>' required>

    <br>
    <br>
    <h3>Abweichung der Wochenarbeitsstunden</h3>

    <table>
        <tbody>
                <tr>
                    <td>Summe:</td>
                    <td>
                      <input type="text" textarea readonly="readonly" name="perEmployeeSumWeeklyWorkingHours"
                      value= '<?php
                      echo $perEmployeeSumWeeklyWorkingHours; ?>'>
                    </td>
                </tr>
                <tr>
                    <td>Durchschnitt</td>
                    <td><input type="text" textarea readonly="readonly" name="perEmployeeAverageWeeklyWorkingHours"
                    value= '<?php
                    echo $perEmployeeAverageWeeklyWorkingHours; ?>'</td>
                </tr>
                <tr>
                    <td>Minimum:</td>
                    <td>
                      <input type="text" textarea readonly="readonly" name="perEmployeeMinWeeklyWorkingHours"
                      value= '<?php
                      echo $perEmployeeMinWeeklyWorkingHours; ?>'></td>
                </tr>
                <tr>
                    <td>Maxmimum:</td>
                    <td>
                      <input type="text" textarea readonly="readonly" name="perEmployeeMaxWeeklyWorkingHours"
                      value= '<?php
                      echo $perEmployeeMaxWeeklyWorkingHours; ?>'></td>
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
                      <td><input type="text" textarea readonly="readonly" name="perEmployeeSumCoreWorkingHours"
                      value= '<?php
                      echo $perEmployeeSumCoreWorkingHours; ?>'></td>
                  </tr>
                  <tr>
                      <td>Durchschnitt</td>
                      <td><input type="text" textarea readonly="readonly" name="perEmployeeAverageCoreWorkingHours"
                      value= '<?php
                      echo $perEmployeeAverageCoreWorkingHours; ?>'></td>
                  </tr>
                  <tr>
                      <td>Minimum:</td>
                      <td><input type="text" textarea readonly="readonly" name="perEmployeeMinCoreWorkingHours"
                      value= '<?php
                      echo $perEmployeeMinCoreWorkingHours; ?>'></td>
                  </tr>
                  <tr>
                      <td>Maxmimum:</td>
                      <td><input type="text" textarea readonly="readonly" name="perEmployeeMaxCoreWorkingHours"
                      value= '<?php
                      echo  $perEmployeeMaxCoreWorkingHours; ?>'></td>
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
