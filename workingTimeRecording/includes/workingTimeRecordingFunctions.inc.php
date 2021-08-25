<?php

function invalidTime($beginTime, $endTime){
    $result;
    if($beginTime > $endTime){
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}

function emptyInput($beginTime, $endTime){
    $result;
    if(empty($beginTime) && empty($endTime)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function oneEmptyInput($beginTime, $endTime){
    if(empty($beginTime) || empty($endTime) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}