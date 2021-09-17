<?php
//Autor der Datei Tamara Romer
//Berechnungsfunktionen für die verschiedenen Evaluation-Abfragen

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

//Ermittelt die Standardabweichung in Sekunden
function getStandardDeviation($allDeviationsInSec){

  if(count($allDeviationsInSec) > 1){
    $stddev=0;
    $average = array_sum($allDeviationsInSec) / count($allDeviationsInSec);

     $sum = 0;
    foreach ($allDeviationsInSec as $element) {
  		 $sum += pow($element - $average, 2);
  	}

    $stddev = sqrt($sum / (count($allDeviationsInSec)-1));
  }else{
    $stddev = 0;
  }

	return $stddev;
}

//Die Funktion formatiert die evaluierten Ergebnisse im Sekundenformat
//zur Ausgabe wieder in das Zeitformat hh:mm:ss um
function formatEvaluatedResults($sum, $average, $min, $max, $standardDeviation){
  $formattedSum = sprintf('%02d:%02d:%02d',
                    ($sum/ 3600),
                    ($sum / 60 % 60),
                    $sum % 60);

$formattedAverage = sprintf('%02d:%02d:%02d',
                    ($average/ 3600),
                    ($average / 60 % 60),
                    $average % 60);


  $formattedMin = sprintf('%02d:%02d:%02d',
                    ($min/ 3600),
                    ($min / 60 % 60),
                    $min % 60);

  $formattedMax = sprintf('%02d:%02d:%02d',
                    ($max/ 3600),
                    ($max / 60 % 60),
                    $max % 60);

$formattedStandardDeviation = sprintf('%02d:%02d:%02d',
                    ($standardDeviation/ 3600),
                    ($standardDeviation / 60 % 60),
                    $standardDeviation % 60);



  $formattedResults = array("Sum"=>$formattedSum, "Average"=>$formattedAverage, "Min"=>$formattedMin, "Max"=>$formattedMax,"StandardDeviation"=>$formattedStandardDeviation);
  return $formattedResults;
}
