<?php
    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = ""; 
    $dbName  = "projecttimerecording";
    $conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);

    if (!$conn){
        die("Conncetion failed: " . mysqli_connect_error());
    }
    