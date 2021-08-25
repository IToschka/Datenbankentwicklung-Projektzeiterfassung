<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <title>Login Projektzeiterfassung</title>
    </head>

    <body>
        <h1>Login Projektzeiterfassung</h1>
        <form action="includes/login.inc.php" method="POST" >
           <p>Personalnummer: <input type="text" name="pnr" placeholder="Personalnummer"> </p>
           <p>Passwort: <input type="password" name="password" placeholder="Passwort"> </p>
           <input type="submit" name="button_login" value="Anmelden">
        </form>
    </body>
</html>


<?php
if(isset($_GET["error"])){
    if($_GET["error"] == "emptyInput"){
        echo "<p>Bitte geben Sie Personalnummer und Passwort ein!</p>";
    }
    elseif ($_GET["error"] == "stmtfailed") {
        echo "<p>Etwas ist schief gelaufen!</p>";
    }
    elseif ($_GET["error"] == "stmtfailed") {
        echo "<p>Etwas ist schief gelaufen!</p>";
    }
    elseif ($_GET["error"] == "incorrectLoginData") {
      echo "<p>Login leider nicht erfolgreiche. Die Personanummer und das Passwort stimmen nicht überein.</p>";

    }

}

?>
