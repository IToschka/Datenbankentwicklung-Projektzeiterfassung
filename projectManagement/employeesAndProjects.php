<!DOCTYPE html>
<html>

    <head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="css/style.css">
   <title>Projekt aus bestehendem Projekt anlegen</title>
    </head>

    <body>
        <h1>Ordnen Sie die Mitarbeiter dem Projekt zu!</h1>
        <form method="post" action="projectAndTaskScript.php">
            <table>
                <tbody>
                    <tr> 
            <td>Personalnummer:</td>
            <td><input type="text" name="pnr"></td>
            </tr>
            <tr>
            <td>ProjektID:</td>
            <td><input type="number" name="projectID"></td>
            </tr>
                </tbody>
            </table>
            <input type="submit" name="button_connect" value="Zuordnen"> 
        
        </form>
    </form>
</body>

</html>