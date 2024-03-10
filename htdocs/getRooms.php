<?php
// Datenbankvariablen
$servername = "localhost";
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
$sql = "SELECT DISTINCT room FROM holderbaum";

// Bereite die Abfrage vor
$stmt = $conn->prepare($sql);

// Führe die Abfrage aus
$stmt->execute();

// Ergebnis in ein Array laden (ohne Duplikate)
$rooms = array();
$stmt->bind_result($room);
while ($stmt->fetch()) {
    $rooms[] = $room;
}

// Schließe die Verbindung
$stmt->close();
$conn->close();

// Ausgabe des Arrays
//print_r($rooms);

$jsonObject = array(
  "rooms" => $rooms
);

// Gib das JSON Objekt aus
echo json_encode($jsonObject, JSON_PRETTY_PRINT);
?>