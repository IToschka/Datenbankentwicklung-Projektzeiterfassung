<?php

function invalidDate($date, $beginDate) {
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
                echo "SQL Statement failed";
                header("location: ../projectsAndTasksNew.php?error=stmtfailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $projectManager);
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
 
    function noNegativeAmountTasks($amountTasks) {
        $result;
        if($amountTasks <0) {
                $result = true;
        }
        else {
                $result = false;
        }
        return $result;
}



function noNegativeProjectManagerPNR($projectManager) {
        $result;
        if($projectManager <0) {
                $result = true;
        }
        else {
                $result = false;
        }
        return $result;
}

function numericProjectID($projectID) {
        $result;
        if($projectID <0) {
                $result = true;
        }
        else {
                $result = false;
        }
        return $result;
}

function numericPNR($pnr) {
        $result;
        if($pnr <0) {
                $result = true;
        }
        else {
                $result = false;
        }
        return $result;
}

/*function countTasks($amountTasks) {
        echo "<form> <table> <tbody>";
                for($i=0; $i <=$amountTasks; $i++) {
                        echo "<tr> <td>Aufgabe $i:</td>
                        <td> <textarea name='task' maxlength='50' cols='50' required></textarea></td>
                        </tr>";
                

        }
        echo "</tbody> </table> <input type='submit' name='button_createTasks' value='Aufgaben anlegen'> </form>";
} */

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
        header("location: ../tasks.php");
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

function fillProject($conn, $projectID){

        $sql = "SELECT ProjectName, BeginDate, Description FROM project, projecttask WHERE ProjectID = ?;"; 
        $stmt = mysqli_stmt_init($conn);
        
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../projectsAndTasksChange.php?error=stmtfailed");
            exit();
            }
        else {
        mysqli_stmt_bind_param($stmt, "s", $projectID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $resultCheck = mysqli_num_rows($result);
        }

        if($resultCheck > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $projectName = $row['ProjectName'];
                    $beginDate = $row['BeginDate'];
                    //$beginDate = $_POST['beginDate']; oder so?
                    //Aufgaben
                    $tasks = array($row['Description']);
                }
            } 
        }

function noAccess($conn, $projectID, $projectManager) {
        $sql = "SELECT * FROM project WHERE ProjectID = ? AND ProjectManagerPNR = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
                echo "SQL Statement failed";
                header("location: ../projectsAndTasksNew.php?error=stmtfailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $projectManager);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);


        if(!mysqli_fetch_assoc($resultData)) {
                $result = true;
        }
        else{
             $result = false;
            }
        return $result;
}

/*function invalidProjectID($conn, $projectID) {
                $sql = "SELECT * FROM project WHERE ProjectID = ?;";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                        echo "SQL Statement failed";
                        header("location: ../projectsAndTasksChange.php?error=stmtfailed");
                    exit();
                }
                mysqli_stmt_bind_param($stmt, "s", $projectID);
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
            } */


/* function insertProject($conn, $projectName, $beginDate, $projectManager) {
        $sql = "SELECT (ProjectName, BeginDate, ProjectManagerPNR) FROM project WHERE ProjectID = 1;";
        $results = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);

        if($resultCheck > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                        echo $row["projectName"] . "<br>";
                        echo $row["beginDate"] . "<br>";
                        echo $row["projectManager"];
                }
        }

        } */

        



/*function displayTasks($conn, $projectID) {
        $sql = "SELECT ProjectTaskID, Description FROM projecttask WHERE ProjectID = ?;";

}  */

/* function changeTask($conn, $task) {
        $sql = "INSERT (Description) INTO projecttask VALUES ?;";
} */
//------------------------------------------------------------------------



//------------------------------------------------------------------------

//Personalnummer und ProjektID muss es geben. Muss mit $conn sein siehe oben. Beides

function invalidpnr($conn, $pnr) {
        $sql =  "SELECT * FROM employee WHERE PNR = ?;";
        $stmt = mysqli_stmt_init($conn);                
        if(!mysqli_stmt_prepare($stmt, $sql)){
                echo "SQL Statement failed";
                header("location: ../employeesAndProjects.php?error=stmtfailed");
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
                echo "SQL Statement failed";
                header("location: ../employeesAndProjects.php?error=stmtfailed");
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
