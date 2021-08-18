<?php
include_once '../../includes/dbh.inc.php';

$sql = "SELECT GeneratePNR() AS PNR;";
$result = mysqli_query($conn, $sql);
$row= mysqli_fetch_assoc($result);
echo $row['PNR'];
