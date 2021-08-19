<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <title>Arbeitszeiterfassung</title>
    </head>

    <body>
        <h1>Erfassungsbereich der Arbeitszeiten</h1>
            <form>
                <?php
                // Schleife an dieser Stelle einfügen - Array Liste mit Projektaufgaben + Zuordnung --> prüfen wo PNR übereinstimmt und dafür eine Zeile mit dem Projekt anlegen
                ?>
                <input type="text" name="Project" placeholder="Projekt 1">
                <br>
                <input type="text" name="Task" placeholder="Projektaufgabe 1">
                <br>
                <input type="time" name="BeginTime" placeholder="Von">
                <br>
                <input type="time" name="EndTime" placeholder="Bis">
                <br>
                <input type="submit" name="save" value="Speichern">
            </form>
    </body>
</html>