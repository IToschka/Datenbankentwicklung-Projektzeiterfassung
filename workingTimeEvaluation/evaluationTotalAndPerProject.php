<?php
  //Autor der Datei Tamara Romer
    include_once '../includes/loginHeader.inc.php';
    include_once '../includes/projectRoleHeader.inc.php';
    include_once '../includes/projectRoleHeader.inc.php';
    include_once '../menu/workingTimeEvaluationMenu.php';
    include_once '../includes/dbh.inc.php';
    include_once 'includes/evaluationTotalAndPerProjectFunctions.inc.php'
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
      $totalSumWeeklyWorkingHours = 0;
      $totalAverageWeeklyWorkingHours = 0;
      $totalMaxWeeklyWorkingHours = 0;
      $totalMinWeeklyWorkingHours = 0;
      $totalStandardDeviationWeeklyWorkingHours = 0;

      $totalSumCoreWorkingHours = 0;
      $totalAverageCoreWorkingHours = 0;
      $totalMinCoreWorkingHours = 0;
      $totalMaxCoreWorkingHours = 0;
      $totalStandardDeviationCoreWorkingHours = 0;


      if (isset($_POST['button_Evaluate'])){

        $evaluationFrom= $_POST['evaluationFrom'];
        $evaluationTo = $_POST['evaluationTo'];

        if(invalidEvaluationDate($evaluationFrom, $evaluationTo) !== false){
            header("location: evaluationTotalAndPerProject.php?error=evaluationDate");
            exit();
        }


        $resultWeeklyWorkingHoursTotal = evaluateWeeklyWorkingsHoursTotal($conn, $evaluationFrom, $evaluationTo);
        $totalSumWeeklyWorkingHours = $resultWeeklyWorkingHoursTotal["Sum"];
        $totalAverageWeeklyWorkingHours = $resultWeeklyWorkingHoursTotal["Average"];
        $totalMinWeeklyWorkingHours = $resultWeeklyWorkingHoursTotal["Min"];
        $totalMaxWeeklyWorkingHours = $resultWeeklyWorkingHoursTotal["Max"];
        $totalStandardDeviationWeeklyWorkingHours = $resultWeeklyWorkingHoursTotal["StandardDeviation"];



        $resultCoreWorkingHoursTotal = evaluateCoreWorkingTimeTotal($conn, $evaluationFrom, $evaluationTo);
        $totalSumCoreWorkingHours = $resultCoreWorkingHoursTotal["Sum"];
        $totalAverageCoreWorkingHours = $resultCoreWorkingHoursTotal["Average"];
        $totalMinCoreWorkingHours = $resultCoreWorkingHoursTotal["Min"];
        $totalMaxCoreWorkingHours = $resultCoreWorkingHoursTotal["Max"];
        $totalStandardDeviationCoreWorkingHours = $resultCoreWorkingHoursTotal["StandardDeviation"];
      }
       ?>

    <form action="evaluationTotalAndPerProject.php" method="POST" >

      Von: <input type="date" name="evaluationFrom" value= '<?php
      echo $evaluationFrom; ?>' required>
      Bis: <input type="date" name="evaluationTo"  value= '<?php
      echo $evaluationTo; ?>' required>

      <br>
      <br>
      <h2>Übersicht über alle Abweichungen</h2>
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
                  <input type="text" textarea readonly="readonly" name="totalSumWeeklyWorkingHours"
                    value= '<?php echo $totalSumWeeklyWorkingHours; ?>'>
                </td>
                <td>
                  <input type="text" textarea readonly="readonly" name="totalSumCoreWorkingHours"
                    value= '<?php echo $totalSumCoreWorkingHours; ?>'></td>
            </tr>
            <tr>
                <td>Durchschnitt</td>
                <td>
                  <input type="text" textarea readonly="readonly" name="totalAverageWeeklyWorkingHours"
                      value= '<?php echo $totalAverageWeeklyWorkingHours; ?>'>
                </td>
                <td>
                  <input type="text" textarea readonly="readonly" name="totalAverageCoreWorkingHours"
                    value= '<?php echo $totalAverageCoreWorkingHours; ?>'></td>
            </tr>
            <tr>
                <td>Minimum:</td>
                <td>
                  <input type="text" textarea readonly="readonly" name="totalMinWeeklyWorkingHours"
                    value= '<?php echo $totalMinWeeklyWorkingHours; ?>'>
                </td>
                <td>
                  <input type="text" textarea readonly="readonly" name="totalMinCoreWorkingHours"
                    value= '<?php echo $totalMinCoreWorkingHours; ?>'>
                </td>
            </tr>
            <tr>
                <td>Maxmimum:</td>
                <td>
                  <input type="text" textarea readonly="readonly" name="totalMaxWeeklyWorkingHours"
                    value= '<?php echo $totalMaxWeeklyWorkingHours; ?>'>
                </td>
                <td>
                  <input type="text" textarea readonly="readonly" name="totalMaxCoreWorkingHours"
                    value= '<?php echo  $totalMaxCoreWorkingHours; ?>'>
                </td>
              </tr>
                <tr>
                <td>Standardabweichung:</td>
                <td>
                  <input type="text" textarea readonly="readonly" name="totalStandardDeviationWeeklyWorkingHours"
                    value= '<?php echo $totalStandardDeviationWeeklyWorkingHours; ?>'>
                </td>
                <td>
                  <input type="text" textarea readonly="readonly" name="totalStandardDeviationCoreWorkingHours"
                      value= '<?php echo  $totalStandardDeviationCoreWorkingHours; ?>'>
                </td>
              </tr>
          </tbody>
      </table>


      <input type="submit" name="button_Evaluate" value = "Auswerten">


    </form>
    <br>
    <br>

    <?php
      if(isset($_GET["error"])){

        if($_GET["error"] == "evaluationDate") {
            echo "<p>Die zu evaluierende Datum ist nicht zulässig. Das Von-Datum sollte nicht nach dem Bis-Datum liegen!</p>";
        }
      }
    ?>

    <br>
    <h2>Abweichung pro Projekt</h2>

    <br>
    <div class="evaluationTable">
      <table style="border: thin solid black; width: 100%">
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
             $projectIds = getAllProjectIds($conn, $evaluationFrom);

            foreach ($projectIds as $element){

              $projectId  = $element;
              $perProjectSumWeeklyWorkingHours = 0;
              $perProjectAverageWeeklyWorkingHours = 0;
              $perProjectMinWeeklyWorkingHours = 0;
              $perProjectMaxWeeklyWorkingHours = 0;
              $perProjectStandardDeviationWeeklyWorkingHours = 0;

              $perProjectSumCoreWorkingHours = 0;
              $perProjectAverageCoreWorkingHours = 0;
              $perProjectMinCoreWorkingHours = 0;
              $perProjectMaxCoreWorkingHours = 0;
              $perProjectStandardDeviationWeeklyWorkingHours = 0;

              $anzahl = getEmployeesPerProject($conn, $projectId);

              if(getEmployeesPerProject($conn, $projectId ) == 0){
                //Wird ausgegeben wenn kein Mitarbeiter für das Projekt eingetragen ist
                $perProjectSumWeeklyWorkingHours = "kein MA";
                $perProjectAverageWeeklyWorkingHours = "kein MA";
                $perProjectMinWeeklyWorkingHours = "kein MA";
                $perProjectMaxWeeklyWorkingHours = "kein MA";
                $perProjectStandardDeviationWeeklyWorkingHours = "kein MA";

                $perProjectSumCoreWorkingHours = "kein MA";
                $perProjectAverageCoreWorkingHours = "kein MA";
                $perProjectMinCoreWorkingHours = "kein MA";
                $perProjectMaxCoreWorkingHours = "kein MA";
                $perProjectStandardDeviationWeeklyWorkingHours = "kein MA";

              }else{
                $resultWeeklyWorkingHoursPerProject = evaluateWeeklyWorkingsHoursPerProject($conn, $evaluationFrom, $evaluationTo,$projectId );
                $perProjectSumWeeklyWorkingHours = $resultWeeklyWorkingHoursPerProject["Sum"];
                $perProjectAverageWeeklyWorkingHours = $resultWeeklyWorkingHoursPerProject["Average"];
                $perProjectMinWeeklyWorkingHours = $resultWeeklyWorkingHoursPerProject["Min"];
                $perProjectMaxWeeklyWorkingHours = $resultWeeklyWorkingHoursPerProject["Max"];
                $perProjectStandardDeviationWeeklyWorkingHours = $resultWeeklyWorkingHoursPerProject["StandardDeviation"];


                $resultCoreWorkingHoursPerProject = evaluateCoreWorkingTimePerProject($conn, $evaluationFrom, $evaluationTo,$projectId );
                $perProjectSumCoreWorkingHours =   $resultCoreWorkingHoursPerProject["Sum"];
                $perProjectAverageCoreWorkingHours = $resultCoreWorkingHoursPerProject["Average"];
                $perProjectMinCoreWorkingHours =   $resultCoreWorkingHoursPerProject["Min"];
                $perProjectMaxCoreWorkingHours =   $resultCoreWorkingHoursPerProject["Max"];
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
      <em>k.M. = kein Mitarbeiter ist für das Projekt eingetragen</em>


      </div>
      <br>


    </body>
</html>
