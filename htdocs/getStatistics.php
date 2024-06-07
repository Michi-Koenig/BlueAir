<?php

$project = $_GET['project'];

// Zugangsdaten zum SQL-Datenbank
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

//__________________________________________________________________________________

// SQL-Abfrage wann die erste Messung gewesen ist
$sql = "SELECT DATE_Format(MIN(zeitstempel),'%d-%m-%Y')  AS oldestTimestamp FROM " .$project;

// Führe die Abfrage aus
$oldestTimestamp = $conn->query($sql);

if ($oldestTimestamp) {
    // Holen Sie das Ergebnis als assoziatives Array
    $row = $oldestTimestamp->fetch_assoc();
    $oldTimestamp =$row['oldestTimestamp'];

}
//__________________________________________________________________________________

// SQL-Abfrage wann die letzte Messung gewesen ist
$sql = "SELECT DATE_Format(MAX(zeitstempel),'%d-%m-%Y')  AS newestTimestamp FROM " .$project;

// Führe die Abfrage aus
$newestTimestamp = $conn->query($sql);

if ($newestTimestamp) {
    // Holen Sie das Ergebnis als assoziatives Array
    $row = $newestTimestamp->fetch_assoc();
    $newTimestamp =$row['newestTimestamp'];
    
}
//__________________________________________________________________________________

// SQL-Abfrage zählt alle Geräte in einem Projekt
$sql = "SELECT COUNT(*) AS numberOfDevices FROM ( SELECT DISTINCT `device`, `room` FROM " .$project." ) AS einzigartige_kombinationen";

// Führe die Abfrage aus
$numberOfDevices = $conn->query($sql);

if ($numberOfDevices) {
    // Holen Sie das Ergebnis als assoziatives Array
    $row = $numberOfDevices->fetch_assoc();
    $devices =$row['numberOfDevices'];
    
}
//__________________________________________________________________________________

// SQL-Abfrage zählt alle Räume in einem Projekt
$sql = "SELECT COUNT(DISTINCT `room`) AS numberOfRooms FROM " .$project;

// Führe die Abfrage aus
$numberOfRooms = $conn->query($sql);

if ($numberOfRooms) {
    // Holen Sie das Ergebnis als assoziatives Array
    $row = $numberOfRooms->fetch_assoc();
    $rooms =$row['numberOfRooms'];
    
}
//__________________________________________________________________________________

// Verbindung schließen
$conn->close();

// Erstelle das JSON Objekt
$jsonObject = array(
    //"project" => $project,
    "oldTimestamp" => $oldTimestamp,
    "newTimestamp" => $newTimestamp,
    "devices" => $devices,
    "rooms" => $rooms
);

// Gib das JSON Objekt aus
echo json_encode($jsonObject, JSON_PRETTY_PRINT);

?>
