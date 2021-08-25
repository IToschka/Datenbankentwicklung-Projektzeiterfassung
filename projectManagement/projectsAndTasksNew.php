<!DOCTYPE html>
<html>

    <head>
   <meta charset="utf-8">
   <title>Neues Projekt anlegen </title>
    </head>

    <body>
        <form method="post" action="tasks.html"> <!--hier muss ein php Skript rein, das die Daten in die DB speichert und zur nächsten Seite weiterleitet --> 
            <p>Projekttitel:</p><textarea name="projectname" cols="50"></textarea>
            <p>Starttermin: <input type="date" name="BeginDate"> </p>
            <p>Anzahl Aufgaben <input type="number" name="AmountTasks"> </p>
            <input type="submit" name="confirm" value="Bestätigen"> 
         </form>
            
    </body>

</html>