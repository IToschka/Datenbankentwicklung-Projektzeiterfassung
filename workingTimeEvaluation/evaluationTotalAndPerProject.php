<?php
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

        if(invalidEvaluationDate($evaluationFrom, $evaluationTo) !== false){
            header("location: evaluationTotalAndPerProject.php?error=evaluationDate");
            exit();
        }


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
    <h2>Gesamtübersicht</h2>
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
     <table>
       <thead>
         <tr>
             <th></th>
             <th  colspan ="5" style="border: 1px solid #CF261E">Abweichung der Wochenarbeitsstunden</th>
             <th colspan ="5" style="border: 1px solid #CF261E">Abweichung der KErnarbeitszeiten</th>
         </tr>

       </thead>
       <tbody>
         <tr>
             <td style="border: 1px solid #CF261E">ProjectID</th>
             <td style="border: 1px solid #CF261E">Summe</td>
             <td style="border: 1px solid #CF261E">Durchschnitt</td>
             <td style="border: 1px solid #CF261E">Minimum</td>
             <td style="border: 1px solid #CF261E">Maximum</td>
             <td style="border: 1px solid #CF261E">Standardabweichung</td>

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
            $perProjectSumWeeklyWorkingHours = 0;
            $perProjectAverageWeeklyWorkingHours = 0;
            $perProjectMinWeeklyWorkingHours = 0;
            $perProjectMaxWeeklyWorkingHours = 0;


          if(getEmployeesPerProject($conn, $element) == 0){
            $perProjectSumWeeklyWorkingHours = "-";
            $perProjectAverageWeeklyWorkingHours = "-";
            $perProjectMinWeeklyWorkingHours = "-";
            $perProjectMaxWeeklyWorkingHours = "-";
          }else{
            $resultWeeklyWorkingHoursPerProject = evaluateWeeklyWorkingsHoursPerProject($conn, $evaluationFrom, $evaluationTo,$element);
            $perProjectSumWeeklyWorkingHours = $resultWeeklyWorkingHoursPerProject["Sum"];
            $perProjectAverageWeeklyWorkingHours = $resultWeeklyWorkingHoursPerProject["Average"];
            $perProjectMinWeeklyWorkingHours = $resultWeeklyWorkingHoursPerProject["Min"];
            $perProjectMaxWeeklyWorkingHours = $resultWeeklyWorkingHoursPerProject["Max"];


            $resultCoreWorkingHoursPerProject = evaluateCoreWorkingTimePerProject($conn, $evaluationFrom, $evaluationTo,$element);
            $perProjectSumCoreWorkingHours =   $resultCoreWorkingHoursPerProject["Sum"];
            $perProjectAverageCoreWorkingHours = $resultCoreWorkingHoursPerProject["Average"];
            $perProjectMinCoreWorkingHours =   $resultCoreWorkingHoursPerProject["Min"];
            $perProjectMaxCoreWorkingHours =   $resultCoreWorkingHoursPerProject["Max"];


          }

            echo ' <tr>
                      <td>'. $element .'</td>
                      <td>'. $perProjectSumWeeklyWorkingHours .'</td>
                      <td>'. $perProjectAverageWeeklyWorkingHours  .'</td>
                      <td>'. $perProjectMinWeeklyWorkingHours .'</td>
                      <td>'. $perProjectMaxWeeklyWorkingHours .'</td>
                      <td>'. 0 .'</td>
                      <td>'. $perProjectSumCoreWorkingHours .'</td>
                      <td>'. $perProjectAverageCoreWorkingHours  .'</td>
                      <td>'. $perProjectMinCoreWorkingHours .'</td>
                      <td>'. $perProjectMaxCoreWorkingHours .'</td>
                      <td>'. 0 .'</td>
                  </tr>';

          }

        }

      ?>
      </tbody>
    </table>

</center>
    </body>
</html>
