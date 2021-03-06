<?php
    //Autor der Datei Tamara Romer

    include_once '../includes/loginHeader.inc.php';
    include_once '../includes/projectRoleHeader.inc.php';
    include_once '../menu/workingTimeEvaluationMenu.php';
    include_once '../includes/dbh.inc.php';
    include_once 'includes/evaluationPerEmployeeFunctions.inc.php'
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

      <?php
        $evaluatedPnr ="";

        $perEmployeeSumWeeklyWorkingHours = 0;
        $perEmployeeAverageWeeklyWorkingHours = 0;
        $perEmployeeMinWeeklyWorkingHours = 0;
        $perEmployeeMaxWeeklyWorkingHours = 0;
        $perEmployeeStandardDeviationWeeklyWorkingHours = 0;


        $perEmployeeSumCoreWorkingHours = 0;
        $perEmployeeAverageCoreWorkingHours = 0;
        $perEmployeeMinCoreWorkingHours = 0;
        $perEmployeeMaxCoreWorkingHours = 0;
        $perEmployeeStandardDeviationCoreWorkingHours = 0;


        if (isset($_POST['button_Evaluate'])){
          $evaluationFrom = $_POST['evaluationFrom'];
          $evaluationTo = $_POST['evaluationTo'];
          $evaluatedPnr = $_POST['evaluatedPnr'];

          if(invalidEvaluationDate($evaluationFrom, $evaluationTo) !== false){
            header("location: evaluationPerEmployee.php?error=evaluationDate");
            exit();
          }

          if(invalidPnr($evaluatedPnr) !== false){
            header("location: evaluationPerEmployee.php?error=invalidPnr");
            exit();
          }

          if(pnrNotExists($conn, $evaluatedPnr)!== false){
            header("location: evaluationPerEmployee.php?error=pnrNotExists");
            exit();
          }


          $resultWeeklyWorkingHoursPerEmployee = evaluateWeeklyWorkingsHoursPerEmployee($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr);
          $perEmployeeSumWeeklyWorkingHours = $resultWeeklyWorkingHoursPerEmployee["Sum"];
          $perEmployeeAverageWeeklyWorkingHours = $resultWeeklyWorkingHoursPerEmployee["Average"];
          $perEmployeeMinWeeklyWorkingHours = $resultWeeklyWorkingHoursPerEmployee["Min"];
          $perEmployeeMaxWeeklyWorkingHours = $resultWeeklyWorkingHoursPerEmployee["Max"];
          $perEmployeeStandardDeviationWeeklyWorkingHours = $resultWeeklyWorkingHoursPerEmployee["StandardDeviation"];



          $resultCoreWorkingHoursPerEmployee = evaluateCoreWorkingTimePerEmployee($conn, $evaluationFrom, $evaluationTo, $evaluatedPnr);
          $perEmployeeSumCoreWorkingHours = $resultCoreWorkingHoursPerEmployee["Sum"];
          $perEmployeeAverageCoreWorkingHours = $resultCoreWorkingHoursPerEmployee["Average"];
          $perEmployeeMinCoreWorkingHours = $resultCoreWorkingHoursPerEmployee["Min"];
          $perEmployeeMaxCoreWorkingHours = $resultCoreWorkingHoursPerEmployee["Max"];
          $perEmployeeStandardDeviationCoreWorkingHours = $resultCoreWorkingHoursPerEmployee["StandardDeviation"];
        }
      ?>

      <form action="evaluationPerEmployee.php" method="POST" >

        Von: <input type="date" name="evaluationFrom" value= '<?php echo $evaluationFrom; ?>' required>
        Bis: <input type="date" name="evaluationTo"  value= '<?php echo $evaluationTo; ?>' required> <br>
        Personalnummer: <input type="text" name="evaluatedPnr" value= '<?php echo htmlentities($evaluatedPnr); ?>' required>

        <br>
        <br>
        <h2>??bersicht ??ber alle Abweichungen eines Mitarbeiters</h2>
        <table class="formTable">
          <thead>
            <tr>
              <th></th>
              <th>Abweichung der Wochenarbeitsstunden</th>
              <th>Abweichung der Kernarbeitszeit</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Summe:</td>
              <td>
                <input type="text" textarea readonly="readonly" name="perEmployeeSumWeeklyWorkingHours"
                    value= '<?php echo $perEmployeeSumWeeklyWorkingHours; ?>'>
              </td>
              <td>
                <input type="text" textarea readonly="readonly" name="perEmployeeSumCoreWorkingHours"
                    value= '<?php echo $perEmployeeSumCoreWorkingHours; ?>'>
              </td>
            </tr>
            <tr>
              <td>Durchschnitt</td>
              <td>
                <input type="text" textarea readonly="readonly" name="perEmployeeAverageWeeklyWorkingHours"
                    value= '<?php echo $perEmployeeAverageWeeklyWorkingHours; ?>'>
              </td>
              <td>
                <input type="text" textarea readonly="readonly" name="perEmployeeAverageCoreWorkingHours"
                    value= '<?php echo $perEmployeeAverageCoreWorkingHours; ?>'>
              </td>
            </tr>
            <tr>
              <td>Minimum:</td>
              <td>
                <input type="text" textarea readonly="readonly" name="perEmployeeMinWeeklyWorkingHours"
                    value= '<?php echo $perEmployeeMinWeeklyWorkingHours; ?>'>
              </td>
              <td>
                <input type="text" textarea readonly="readonly" name="perEmployeeMinCoreWorkingHours"
                    value= '<?php echo $perEmployeeMinCoreWorkingHours; ?>'>
              </td>
            </tr>
            <tr>
              <td>Maxmimum:</td>
              <td>
                <input type="text" textarea readonly="readonly" name="perEmployeeMaxWeeklyWorkingHours"
                    value= '<?php echo $perEmployeeMaxWeeklyWorkingHours; ?>'>
              </td>
              <td>
                <input type="text" textarea readonly="readonly" name="perEmployeeMaxCoreWorkingHours"
                    value= '<?php echo  $perEmployeeMaxCoreWorkingHours; ?>'></td>
            </tr>
              <tr>
              <td>Standardabweichung:</td>
              <td>
                <input type="text" textarea readonly="readonly" name="perEmployeeStandardDeviationWeeklyWorkingHours"
                    value= '<?php echo $perEmployeeStandardDeviationWeeklyWorkingHours; ?>'>
              </td>
              <td>
                <input type="text" textarea readonly="readonly" name="perEmployeeStandardDeviationCoreWorkingHours"
                    value= '<?php echo  $perEmployeeStandardDeviationCoreWorkingHours; ?>'></td>
            </tr>
          </tbody>

        </table>
        <br>
        <br>
        <input type="submit" name="button_Evaluate" value = "Auswerten">

      </form>
    <br>
    <br>

    <?php
      if(isset($_GET["error"])){

        if($_GET["error"] == "evaluationDate") {
            echo "<p>Die zu evaluierende Datum ist nicht zul??ssig. Das Von-Datum sollte nicht nach dem Bis-Datum liegen!</p>";
        }
        elseif($_GET["error"] == "invalidPnr") {
            echo "<p>Die Personalnummer ist ung??ltig. Es sind nur Nummern von 0-9 zul??ssig!</p>";
        }
        elseif($_GET["error"] == "pnrNotExists") {
            echo "<p>Die Personalnummer existiert nicht!</p>";
        }
      }
    ?>


    <br>
    <h2>Abweichungen des Mitarbeiters pro Projekt</h2>
    <br>
    <table class="evaluationTable" style="width: 100%">
      <thead>
         <tr>
            <th></th>
            <th  colspan ="5">Abweichung der Wochenarbeitsstunden</th>
            <th colspan ="5">Abweichung der Kernarbeitszeiten</th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <td>ProjectID</th>
            <td>Summe</td>
            <td>Durchschnitt</td>
            <td>Minimum</td>
            <td>Maximum</td>
            <td>Standardabweichung</td>

            <td>Summe</td>
            <td>Durchschnitt</td>
            <td>Minimum</td>
            <td>Maximum</td>
            <td>Standardabweichung</td>
         </tr>

          <?php
            if (isset($_POST['button_Evaluate'])){
              $projectIds =  getAllProjectIdsFromEmployee($conn, $evaluatedPnr, $evaluationFrom);

              foreach ($projectIds as $element){
                  $projectId = $element;
                  $perProjectSumWeeklyWorkingHours = 0;
                  $perProjectAverageWeeklyWorkingHours = 0;
                  $perProjectMinWeeklyWorkingHours = 0;
                  $perProjectMaxWeeklyWorkingHours = 0;
                  $perProjectStandardDeviationWeeklyWorkingHours = 0;

                  $perProjectSumCoreWorkingHours = 0;
                  $perProjectAverageCoreWorkingHours = 0;
                  $perProjectMinCoreWorkingHours = 0;
                  $perProjectMaxCoreWorkingHours = 0;
                  $perProjectStandardDeviationCoreWorkingHours = 0;



                  if(getWorkingDaysPerProject($conn, $evaluationFrom, $evaluationTo, $projectId, $evaluatedPnr) == 0){
                    //Hat der Mitarbeiter im Angegebenen Zeitraum nicht an dem Projekt gearbeitet wird ein Strich eingetragen
                    $perProjectSumWeeklyWorkingHours = "-";
                    $perProjectAverageWeeklyWorkingHours = "-";
                    $perProjectMinWeeklyWorkingHours = "-";
                    $perProjectMaxWeeklyWorkingHours = "-";
                    $perProjectStandardDeviationWeeklyWorkingHours = "-";

                    $perProjectSumCoreWorkingHours = "-";
                    $perProjectAverageCoreWorkingHours = "-";
                    $perProjectMinCoreWorkingHours = "-";
                    $perProjectMaxCoreWorkingHours = "-";
                    $perProjectStandardDeviationCoreWorkingHours = "-";
                  }else{

                    $resultWeeklyWorkingHoursPerProject = evaluateWeeklyWorkingsHoursPerProject($conn, $evaluationFrom, $evaluationTo,$projectId , $evaluatedPnr);
                    $perProjectSumWeeklyWorkingHours = $resultWeeklyWorkingHoursPerProject["Sum"];
                    $perProjectAverageWeeklyWorkingHours = $resultWeeklyWorkingHoursPerProject["Average"];
                    $perProjectMinWeeklyWorkingHours = $resultWeeklyWorkingHoursPerProject["Min"];
                    $perProjectMaxWeeklyWorkingHours = $resultWeeklyWorkingHoursPerProject["Max"];
                    $perProjectStandardDeviationWeeklyWorkingHours = $resultWeeklyWorkingHoursPerProject["StandardDeviation"];


                    $resultCoreWorkingHoursPerProject = evaluateCoreWorkingTimePerProject($conn, $evaluationFrom, $evaluationTo,$projectId,$evaluatedPnr);
                    $perProjectSumCoreWorkingHours = $resultCoreWorkingHoursPerProject["Sum"];
                    $perProjectAverageCoreWorkingHours = $resultCoreWorkingHoursPerProject["Average"];
                    $perProjectMinCoreWorkingHours = $resultCoreWorkingHoursPerProject["Min"];
                    $perProjectMaxCoreWorkingHours = $resultCoreWorkingHoursPerProject["Max"];
                    $perProjectStandardDeviationCoreWorkingHours = $resultCoreWorkingHoursPerProject["StandardDeviation"];

                  }



                  echo ' <tr>
                            <td>'. $projectId  .'</td>
                            <td>'. $perProjectSumWeeklyWorkingHours .'</td>
                            <td>'. $perProjectAverageWeeklyWorkingHours  .'</td>
                            <td>'. $perProjectMinWeeklyWorkingHours .'</td>
                            <td>'. $perProjectMaxWeeklyWorkingHours .'</td>
                            <td>'. $perProjectStandardDeviationWeeklyWorkingHours .'</td>
                            <td>'. $perProjectSumCoreWorkingHours .'</td>
                            <td>'. $perProjectAverageCoreWorkingHours  .'</td>
                            <td>'. $perProjectMinCoreWorkingHours .'</td>
                            <td>'. $perProjectMaxCoreWorkingHours .'</td>
                            <td>'. $perProjectStandardDeviationCoreWorkingHours .'</td>
                        </tr>';

              }

            }
          ?>
      </tbody>
    </table>
    <br>
      <em>"-" = Der Mitarbeiter hat in dem Zeitraum nicht am Projekt gearbeitet</em>

    <br>


    </body>
</html>
