<?php

$typOfTime = "day";           //$_GET['typOfTime'];
//$timeperiod =           //$_GET['timeperiod']; baumschulenweg holderbaum
$project = "holderbaum";
$room = "schlafen";

if ($typOfTime == "day"){
    $arrayTime = array(0,2,4,6,8,10,12,14,16,18,20,22);
    $arrayTemp1 = array("19", "20", "20", "21", "19", "21", "19", "20", "21", "19", "21", "19");
    $arrayHum1 = array("50", "51", "52", "51", "51", "50", "51", "52", "51", "51", "50", "51");
    $arrayPres1 = array("965", "965", "963", "960", "961", "965", "963", "963", "960", "961", "965", "963");
    $arrayCo21 = array("450", "455", "454", "455", "455","450", "455", "454", "455", "455","450", "455");

    $arrayTemp2 = array("23", "22", "21", "21", "22", "21", "22", "21", "21", "22", "21", "22");
    $arrayHum2 = array("45", "47", "47", "48", "49", "47", "47", "47", "48", "49", "47", "47");
    $arrayPres2 = array("954", "956", "957", "958", "955", "957", "958", "957", "958", "955", "957", "958");
    $arrayCo22 = array("490", "485", "480", "480", "478", "485", "480", "480", "480", "478", "485", "480");
}elseif($typOfTime == "week"){
    $arrayTime = array("MO", "DI", "MI", "DO", "FR", "SA", "SO");
    $arrayTemp1 = array("19", "20", "20", "21", "19", "21", "19");
    $arrayHum1 = array("50", "51", "52", "51", "51", "50", "51");
    $arrayPres1 = array("965", "965", "963", "960", "961", "965", "963");
    $arrayCo21 = array("450", "455", "454", "455", "455","450", "455");

    $arrayTemp2 = array("23", "22", "21", "21", "22", "21", "22");
    $arrayHum2 = array("45", "47", "47", "48", "49", "47", "47");
    $arrayPres2 = array("954", "956", "957", "958", "955", "957", "958");
    $arrayCo22 = array("490", "485", "480", "480", "478", "485", "480");
}elseif($typOfTime == "month"){
    $arrayTime = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30);
    $arrayTemp1 = array("19", "20", "20", "21", "19", "21", "19", "20", "21", "19", "21", "19", "19", "20", "20", "21", "19", "21", "19", "20", "21", "19", "21", "19", "21", "19", "21", "19", "19", "20");
    $arrayHum1 = array("50", "51", "52", "51", "51", "50", "51", "52", "51", "51", "50", "51", "50", "51", "52", "51", "51", "50", "51", "52", "51", "51", "50", "51", "50", "51", "50", "51", "52", "51");
    $arrayPres1 = array("965", "965", "963", "960", "961", "965", "963", "963", "960", "961", "965", "963", "965", "965", "963", "960", "961", "965", "963", "963", "960", "961", "965", "963", "960", "961", "965", "963", "963", "960");
    $arrayCo21 = array("450", "455", "454", "455", "455","450", "455", "454", "455", "455","450", "455", "450", "455", "454", "455", "455","450", "455", "454", "455", "455","450", "455", "455", "455","450", "455", "454", "455");

    $arrayTemp2 = array("19", "20", "20", "21", "19", "21", "19", "20", "21", "19", "21", "19", "19", "20", "20", "21", "19", "21", "19", "20", "21", "19", "21", "19", "21", "19", "21", "19", "19", "20");
    $arrayHum2 = array("50", "51", "52", "51", "51", "50", "51", "52", "51", "51", "50", "51", "50", "51", "52", "51", "51", "50", "51", "52", "51", "51", "50", "51", "50", "51", "50", "51", "52", "51");
    $arrayPres2 = array("965", "965", "963", "960", "961", "965", "963", "963", "960", "961", "965", "963", "965", "965", "963", "960", "961", "965", "963", "963", "960", "961", "965", "963", "960", "961", "965", "963", "963", "960");
    $arrayCo22 = array("450", "455", "454", "455", "455","450", "455", "454", "455", "455","450", "455", "450", "455", "454", "455", "455","450", "455", "454", "455", "455","450", "455", "455", "455","450", "455", "454", "455");
    // arrayTime muss für den Monat noch variabel gestalltet werden
}

$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "airmonitoring";
// Erstelle eine Verbindung
$conn = new mysqli($servername, $username, $password, $dbname);

// Überprüfe die Verbindung
if ($conn->connect_error) {
  die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// SQL-Abfrage mit DISTINCT
$sql = "SELECT DISTINCT geraet FROM " .$project;

// Führe die Abfrage aus
$result = $conn->query($sql);

$devices = $result->num_rows; // SQL abfrage
//echo $devices;


$devices = 2;

// Erstelle die Arrays
//$arrayTime = array(1,2,3,4,5);


$arrayData =array($arrayTemp1, $arrayHum1, $arrayPres1, $arrayCo21, $arrayTemp2, $arrayHum2, $arrayPres2, $arrayCo22);

// Erstelle das JSON Objekt
$jsonObject = array(
    "project" => $project,
    "room" => $room,
    "devices" => $devices,
    "time" => $arrayTime,
    "data" => $arrayData
);

// Gib das JSON Objekt aus
echo json_encode($jsonObject, JSON_PRETTY_PRINT);

?>
