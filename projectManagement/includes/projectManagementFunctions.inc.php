<?php

function emptyInput($projectName, $beginDate) {
        $result;
        if(empty($projectName) || empty($beginDate)) {
            $result = true;
        }
        else {
            $result = false;
        }
        return $result;
}

function createProject($conn, $projectName, $beginDate) {
$sql = "INSERT INTO project (ProjectName, BeginDate) VALUES (?, ?)";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL Statement failed";
        header("location: ../projectsAndTasksNew.php?error=stmtfailed");
        exit();
}
else {
        mysqli_stmt_bind_param($stmt, "ss", $projectName, $beginDate);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("location: ../projectsAndTasksNew.php?error=none");
        exit();

}
}
