<?php
    session_start();
    if (isset($_SESSION['Username']) == false){
        header ('Location: http://localhost:8080/index.html')
        exit;
    }
?>