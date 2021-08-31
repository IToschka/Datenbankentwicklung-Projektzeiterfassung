<?php
    include_once '../menu/workingTimeEvaluationMenu.php';
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
    <form action="includes/employeeManagement_Script.inc.php" method="POST" >
      Von: <input type="date" name="evaluationFrom" required>
      Bis: <input type="date" name="evaluationTo" required>
    <br>
    <br>
    <h3>Abweichung der Wochenarbeitsstunden</h3>
    <table>
        <tbody>
                <tr>
                    <td>Summe:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Durchschnitt</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Minimum:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Maxmimum:</td>
                    <td></td>
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
                      <td></td>
                  </tr>
                  <tr>
                      <td>Durchschnitt</td>
                      <td></td>
                  </tr>
                  <tr>
                      <td>Minimum:</td>
                      <td></td>
                  </tr>
                  <tr>
                      <td>Maxmimum:</td>
                      <td></td>
                  </tr>
                  <tr>
                      <td>Standardabweichung:</td>
                      <td></td>
                  </tr>
            </tbody>
        </table>


        <input type="submit" name="button_EmployeeMenu" value="Zurück zum Menü">
        <input type="submit" name="button_create" value = "Erstellen">


    </form>
    <br>
    <br>


</center>
    </body>
</html>
