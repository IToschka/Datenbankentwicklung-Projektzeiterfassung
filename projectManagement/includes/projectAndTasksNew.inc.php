<?php


//create functions
function createProject($conn, $projectName, $beginDate) {
$projectName = mysqli_real_escape_string($conn, $_POST["projectName"]);
$beginDate = mysqli_real_escape_string($conn, $_POST["beginDate"]);

$sql = "INSERT INTO project (ProjectName, BeginDate)
        VALUES (?, ?)";

$stmt = mysqli_stmt_init($conn);

if(!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL Statement failed";
        header("location: ../createEmployee.php?error=stmtfailed");
        exit();
}
else {
        mysqli_stmt_bind_param($stmt, "ss", $projectName, $beginDate);
        my_sqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
}
}


}
/*
Anderes File

include_once '../../includes/dbh.inc.php';

if (isset($_POST['button_create'])) {
    $sql = "SELECT GeneratePNR() AS PNR;";
    $result = mysqli_query($conn, $sql);
    $row= mysqli_fetch_assoc($result);
    $pnr = $row['PNR'];
    
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];
    $coreTimeFrom = $_POST['coreTimeFrom'];
    $coreTimeTo = $_POST['coreTimeTo'];
    $hiringDate = $_POST['hiringDate'];
    $weeklyWorkingHours = $_POST['weeklyWorkingHours'];
    if (isset($_POST['projectManager'])){
        $projectManager = true;
    }else{
        $projectManager = false;
    }

*/

