<?php
$typOfTime = $_GET['typOfTime'];
$timeperiod = $_GET['timeperiod'];
$projekt = $_GET['projekt'];
$room = $_GET['room'];
$divice = $_GET['device'];


try {
    $connection = new mysqli('localhost', 'root', '', 'airmonitoring');
  } catch (Exception $e) {
    die('MySQL-Verbindung fehlgeschlagen: ' . $e->getMessage());
  }

$sql = "SELECT * FROM users WHERE created_at >= '2015-01-01 00:00:00' AND created_at <= '2015-12-31 23:59:49'";
foreach ($pdo->query($sql) as $row) {
   echo $row['vorname']." ".$row['nachname']."<br />";
   echo "E-Mail: ".$row['email']."<br /><br />";
}

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

?>
