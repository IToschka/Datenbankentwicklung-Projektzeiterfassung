<?php

function invalidDate($date, $beginDate) {
        date_default_timezone_set("Europe/Berlin");
        $timestamp = time();
        $date = date("d.m.Y", $timestamp);

        $result;
        if($beginDate < $date) {
            $result = true;
        }
        else {
            $result = false;
        }
        return $result;
}

function invalidProjectManagerPNR($conn, $projectManager) {
        $sql = "SELECT * FROM employee WHERE ProjectManager = '1' AND PNR = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../projectsAndTasksNew.php?error=invalidProjectManager");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $pnr);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);


        if(!mysqli_fetch_assoc($resultData)) {
                $result = true;
        }
        else{
             $result = false;
            }
        return $result;
        mysqli_stmt_close($stmt);
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

function numericPNR($pnr) {
        $result;
        if(!preg_match("/[0-9]/", $pnr)) {
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
                        <td> <textarea name='task' maxlength='50' cols='50' required></textarea></td>
                        </tr>";
                

        }
        echo "</tbody> </table> <input type='submit' name='button_createTasks' value='Aufgaben anlegen'> </form>";
}

function createProject($conn, $projectName, $beginDate, $projectManager) {
$sql = "INSERT INTO project (ProjectName, BeginDate, ProjectManagerPNR) VALUES (?, ?, ?);";
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
        
        exit();

}
}
// wird vermutlich nur einmal aufgerufen für die erste Aufgabe
// Verbindung von Projekt und Aufgabe
// Nummerierung der Aufgaben
function createTasks($conn, $task) { 
        $sql= "INSERT INTO projecttask (Description) VALUES (?);";
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

//------------------------------------------------------------------------

//Personalnummer und ProjektID muss es geben. Muss mit $conn sein siehe oben. Beides

function invalidpnr($conn, $pnr) {
        $sql =  "SELECT * FROM employee WHERE PNR = ?;";
        $stmt = mysqli_stmt_init($conn);                
        if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../employeesAndProjects.php?error=invalidpnr");
        exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $pnr);
        mysqli_stmt_execute($stmt);
        
        $resultData = mysqli_stmt_get_result($stmt);
        
        
        if(!mysqli_fetch_assoc($resultData)) {
                $result = true;
        }
        else{
                $result = false;
                }
        return $result;
        mysqli_stmt_close($stmt);
        }

function invalidProjectID($conn, $projectID) {
        $sql =  "SELECT * FROM project WHERE ProjectID = ?;";
        $stmt = mysqli_stmt_init($conn);                
        if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../employeesAndProjects.php?error=projectID");
        exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $pnr);
        mysqli_stmt_execute($stmt);
        
        $resultData = mysqli_stmt_get_result($stmt);
        
        
        if(!mysqli_fetch_assoc($resultData)) {
                $result = true;
        }
        else{
                $result = false;
                }
        return $result;
        mysqli_stmt_close($stmt);
        }


function createConnection($conn, $pnr, $projectID) {
        $sql = "INSERT INTO employeeproject (PNR, ProjectID) VALUES (?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)) {
                echo "SQL Statement failed";
                header("location: ../employeesAndProjects.php?error=stmtfailed");
                exit();
        }
        else {
                mysqli_stmt_bind_param($stmt, "ss", $pnr, $projectID);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                header("location: ../employeesAndProjects.php?error=none");
}
}

function deleteConnection($conn, $pnr, $projectID) {
        $sql = "DELETE FROM employeeproject  WHERE PNR = ? AND ProjectID = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../employeesAndProjects.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ss", $pnr, $projectID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("location: ../employeesAndProjects.php?error=none1");
        exit();
}




