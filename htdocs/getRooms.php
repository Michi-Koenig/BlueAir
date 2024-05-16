<?php
// Datenbankvariablen
$project = $_GET['project'];
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

// SQL-Abfrage welche Räume es in dem Projekt gibt
$sql = "SELECT DISTINCT room FROM " .$project;

// Führe die Abfrage aus
$result = $conn->query($sql);

// Ergebnisse in ein Array speichern
if ($result->num_rows > 0) {

  $resultArray = array();
  while($row = $result->fetch_assoc()) {
    $resultArray[] = $row['room'];
  }

} else {
  echo "0 Ergebnisse";
}

// Verbindung schließen
$conn->close();

// Erzeuge das JSON Objekt
$jsonObject = array(
  "rooms" => $resultArray
);

//Gib das JSON Objekt aus
echo json_encode($jsonObject, JSON_PRETTY_PRINT);
?>
