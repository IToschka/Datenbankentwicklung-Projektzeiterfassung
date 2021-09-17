<?php

//Autor: Katja Frei

function invalidDate($date, $beginDate) {
        if($beginDate < $date) {
            return "invalidDate";
        }
        else {
            return "";
        }
}


function titleAlreadyExists($conn, $projectName) {
        $sql = "SELECT * FROM project WHERE ProjectName != ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
                echo "SQL Statement failed";
               return "stmtfailed";
        }
        mysqli_stmt_bind_param($stmt, "s", $projectName);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);


        if(!mysqli_fetch_assoc($resultData)) {
                $error = "titleAlreadyExists";
        }
        else{
             $error = "";
            }
        return $error;
        mysqli_stmt_close($stmt);
    }


//New und copy greifen hier zu
function createProject($conn, $projectName, $beginDate, $projectManager) {
$sql = "INSERT INTO project (ProjectName, BeginDate, ProjectManagerPNR) VALUES (?, ?, ?);";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL Statement failed";
        return "stmtfailed";
}
else {
        mysqli_stmt_bind_param($stmt, "sss", $projectName, $beginDate, $projectManager);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return "none";
}
}
        //ProjektID für das Anlegen von neuen Aufgaben zum letzten erstellten Projekt
        function getProjectID($conn){

        $sql = "SELECT MAX(ProjectID) AS MaxProjectID FROM project;";
        $stmt = mysqli_stmt_init($conn);


        if(!mysqli_stmt_prepare($stmt, $sql)){
                header("location: ../projectsAndTasksChange.php?error=stmtfailed");
                exit();
            } else {
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $row= mysqli_fetch_assoc($result);
                    $projectID = $row['MaxProjectID'];
        }
        return $projectID;
  }

//new und copy
function createTasks($conn, $taskID, $projektID, $task) {
        $sql= "INSERT INTO projecttask (ProjectTaskID, ProjectID, TaskDescription) VALUES (?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL Statement failed";
        header("location: ../projectsAndTasksNew.php?error=stmtfailed");
        exit();
}
else {
        mysqli_stmt_bind_param($stmt, "sss", $taskID, $projektID, $task);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        //header("location: ../projectsAndTasksNew.php?error=none");
}
}
// neue Aufgabe anlegen in change
/*function updateTask($conn, $projectTaskID, $projectID, $task) {
        $sql= "INSERT INTO projecttask (ProjectTaskID, ProjectID, TaskDescription) VALUES (?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL Statement failed";
        header("location: ../projectsAndTasksNew.php?error=stmtfailed");
        exit();
}
else {
        mysqli_stmt_bind_param($stmt, "sss", $tasks, $projectID, $projectTaskID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("location: ../projectsAndTasksNew.php?error=none");


}
}
*/
//taskID für change Aufgabe zum hizufügen anzeigen
function getTaskID($conn, $projectID) {
        $sql = "SELECT ProjectTaskID FROM projecttask WHERE ProjectID = ?;";
        $stmt = mysqli_stmt_init($conn);
        $projectTaskID = 0;

        if(!mysqli_stmt_prepare($stmt, $sql)){
                header("location: ../projectsAndTasksChange.php?error=stmtfailed");
                exit();
            } else {
                    mysqli_stmt_bind_param($stmt, "s", $projectID);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $resultCheck = mysqli_num_rows($result);
            }

        if($resultCheck > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $projectTaskID = $row['ProjectTaskID'];
                }

        }
        return $projectTaskID;
}

//------------------------------------------------------------------------

//change und copy
function fillProject($conn, $projectID){

        $sql = "SELECT ProjectName, BeginDate FROM project WHERE ProjectID = ?;";
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../projectsAndTasksChange.php?error=stmtfailed");
            exit();
        } else {
                mysqli_stmt_bind_param($stmt, "s", $projectID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $resultCheck = mysqli_num_rows($result);
        }

        if($resultCheck > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $_SESSION['projectName'] = $row['ProjectName'];
                    $_SESSION['beginDate'] = $row['BeginDate'];

                }

        }
}

 //copy
function fillTasks($conn, $projectID){

                $sql = "SELECT (TaskDescription) FROM projecttask WHERE ProjectID = ?;";
                $stmt = mysqli_stmt_init($conn);

                if(!mysqli_stmt_prepare($stmt, $sql)){
                    header("location: ../projectsAndTasksChange.php?error=stmtfailed");
                    exit();
                } else {
                        mysqli_stmt_bind_param($stmt, "s", $projectID);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $resultCheck = mysqli_num_rows($result);
                }

                if($resultCheck > 0) {
                        $tasks = array();
                        $_SESSION['tasks'] = array();
                        while($row = mysqli_fetch_assoc($result)) {
                                array_push($tasks, $row['TaskDescription']);
                        }
                        $_SESSION['tasks'] = $tasks;
                }
}

// Projektleiter dürfen nur eigene Projekte kopieren, ändern löschen
function noAccess($conn, $projectID, $projectManager) {
        $sql = "SELECT * FROM project WHERE ProjectID = ? AND ProjectManagerPNR = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
                echo "SQL Statement failed";
                header("location: ../projectsAndTasksNew.php?error=stmtfailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ss", $projectID, $projectManager);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);


        if(!mysqli_fetch_assoc($resultData)) {
                $error = "noAccess";
        }
        else{
             $error = "";
            }
        return $error;
}


//delete
function deleteProject($conn, $projectID) {
        $sql = "DELETE FROM project WHERE ProjectID = ?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)) {
                echo "SQL Statement failed";
                header("location: ../projectsAndTasksDelete.php?error=stmtfailed");
                exit();
        }
        else {
                mysqli_stmt_bind_param($stmt, "s", $projectID);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                header("location: ../projectsAndTasksDelete.php?error=none");
                exit();

        }
        }
// ProjektID existiert nicht
function invalidProjectID($conn, $projectID) {
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
                        $error = "invalidProject";
                }
                else{
                     $error = "";
                    }
                return $error;
                mysqli_stmt_close($stmt);
            }



//------------------------------------------------------------------------


// PNR nicht vorhanden
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

        //Kombination aus PNR und Projekt existiert bereits
        function alreadyExisting($conn, $pnr, $projectID) {
                $sql =  "SELECT * FROM employeeproject WHERE PNR != ? AND ProjectID != ?;";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                        echo "SQL Statement failed";
                        header("location: ../employeesAndProjects.php?error=stmtfailed");
                exit();
                }
                mysqli_stmt_bind_param($stmt, "ss", $pnr, $projectID);
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

                // Kombination aus PNR und ProjektID existiert noch nicht
                function noSuchCombination($conn, $pnr, $projectID) {
                        $sql =  "SELECT * FROM employeeproject WHERE PNR = ? AND ProjectID = ?;";
                        $stmt = mysqli_stmt_init($conn);
                        if(!mysqli_stmt_prepare($stmt, $sql)){
                                echo "SQL Statement failed";
                                header("location: ../employeesAndProjects.php?error=stmtfailed");
                        exit();
                        }
                        mysqli_stmt_bind_param($stmt, "ss", $pnr, $projectID);
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

// PNR und ProjektID verknüpfen
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

// PNR und ProjektID trennen
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
