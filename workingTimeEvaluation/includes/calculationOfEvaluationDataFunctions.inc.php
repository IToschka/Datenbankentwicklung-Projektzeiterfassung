<?php
//Berechnungsfunktionen für die Abfragen

//Die Funktion wandelt die Abweichung im Zeitformat in Sekunden um
function deviationInSec($deviation){
  $temp= explode(":", $deviation);
  $deviationInSec = 0;
  $deviationInSec+= (int) $temp[0] * 3600;
  $deviationInSec+= (int) $temp[1] * 60;
  $deviationInSec+= (int) $temp[2];
  return $deviationInSec;
}

//Ermittelt die Summe der Abweichungen in Sekunden
function getSum($deviationInSec,$sum){
  $sum+= (int) $deviationInSec;
  return $sum;
}

//Ermittelt den Durchschnitt der Abweichungen in Sekunden
function getAverage($sum, $numberOfValues){
  $average= $sum/$numberOfValues;
  return  $average;
}

//Ermittelt das Mimimum der Abweichungen in Sekunden
function getMin($deviationInSec, $min){

  if(abs((int)$deviationInSec) < $min || $min == 0){
    return true;
  }else{
    return false;
  }
}

//Ermittelt das Maximum der Abweichungen in Sekunden
function getMax($deviationInSec, $max){
  if(abs((int)$deviationInSec) > $max){
    return true;
  }else{
    return false;
  }
}

//Die Funktion formatiert die evaluierten Ergebnisse im Sekundenformat
//zur Ausgabe wieder in das Zeitformat hh:mm:ss um
function formatEvaluatedResults($sum, $average, $min, $max){
  $formattedSum= sprintf('%02d:%02d:%02d',
                    ($sum/ 3600),
                    ($sum / 60 % 60),
                    $sum % 60);

$formattedAverage= sprintf('%02d:%02d:%02d',
                    ($average/ 3600),
                    ($average / 60 % 60),
                    $average % 60);


  $formattedMin= sprintf('%02d:%02d:%02d',
                    ($min/ 3600),
                    ($min / 60 % 60),
                    $min % 60);

  $formattedMax= sprintf('%02d:%02d:%02d',
                    ($max/ 3600),
                    ($max / 60 % 60),
                    $max % 60);

  $formattedResults = array("Sum"=>$formattedSum, "Average"=>$formattedAverage, "Min"=>$formattedMin, "Max"=>$formattedMax);
  return $formattedResults;
}
