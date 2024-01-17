<?php

// Erstelle die Vriabeln
$project = "Holderbaum";
$room = "Schlafen";
$device = 1;

// Erstelle die Arrays
$arrayTime = array(1, 2, 3, 4, 5);
$arrayTemp = array("19", "20", "20", "21", "19");
$arrayHum = array("50", "51", "52", "51", "51");
$arrayPres = array("965", "965", "963", "960", "961");
$arrayCo2 = array("450", "455", "454", "455", "455");

// Erstelle das JSON Objekt
$jsonObject = array(
    "project" => $project,
    "room" => $room,
    "device" => $device,
    "time" => $arrayTime,
    "temp" => $arrayTemp,
    "hum" => $arrayHum,
    "pres" => $arrayPres,
    "co2" => $arrayCo2
);

// Gib das JSON Objekt aus
echo json_encode($jsonObject, JSON_PRETTY_PRINT);

/*
$array = array();
$array['room'] = "Wohnen";
$array['device'] = 1;
$array['temp'] = array(); // unnötige Deklarierung aber zur lesbarkeit
$array['temp'][] = 22;
$array['temp'][] = 22;
$array['temp'][] = 23;
$array['temp'][] = 24;
$array['temp'][] = 24;
$array['temp'][] = 25;
$array['temp'][] = 23;

echo json_encode($array, JSON_PRETTY_PRINT);
*/


?>
