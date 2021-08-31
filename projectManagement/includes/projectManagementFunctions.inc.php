<?php

function invalidDate($beginDate) {
        $result;
        if($beginDate < $date) {
            $result = true;
        }
        else {
            $result = false;
        }
        return $result;
}

function invalidProjectManagerPNR($projectManager) {
        $result;
        if($projectManager == "SELECT ProjectManagerPNR FROM projects WHERE ProjectManagerPNR = '$projectManager'") {
            $result = true;
        }
        else {
            $result = false;
        }
        return $result;
} 

function numericProjectManagerPNR($projectManager) {
        $result;
        if(!preg_match("/[0-9]/", $projectManager)) {
                $result = true;
        }
        else {
                $result = false;
        }
        return $result;
}

function countTasks($amountTasks) {
        echo "<form> <table> <tbody>";
                for($i=0; $i <=$amountTasks; $i++) {
                        echo "<tr> <td>Aufgabe $i:</td>
                        <td> <textarea name="task" maxlength="50" cols="50" required></textarea></td>
                        </tr>";
                

        }
        echo "</tbody> </table> <input type="submit" name="button_createTasks" value="Aufgaben anlegen"> </form>";
}

function createProject($conn, $projectName, $beginDate, $projectManager, $amountTasks) {
$sql = "INSERT INTO project (ProjectName, BeginDate, ProjectManagerPNR) VALUES (?, ?, ?)";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL Statement failed";
        header("location: ../projectsAndTasksNew.php?error=stmtfailed");
        exit();
}
else {
        mysqli_stmt_bind_param($stmt, "sss", $projectName, $beginDate, $projectManager);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("location: ../projectsAndTasksNew.php?error=none");
        
        countTasks($amountTasks); //muss evtl. vor stmt close
        exit();

}
}
// wird vermutlich nur einmal aufgerufen für die erste Aufgabe
// Verbindung von Projekt und Aufgabe
// Nummerierung der Aufgaben
function createTasks($task) { 
        $sql= "INSERT INTO projecttask ($task) VALUES (?)";
        $stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL Statement failed";
        header("location: ../projectsAndTasksNew.php?error=stmtfailed");
        exit();
}
else {
        mysqli_stmt_bind_param($stmt, "s", $amountTasks);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("location: ../projectsAndTasksNew.php?error=none");
        

}
}




