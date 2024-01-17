<?php
$temperature = $_GET['temperature'];
$humidity = $_GET['humidity'];
$pressure = $_GET['pressure'];
$co2 = $_GET['co2'];
$projekt = $_GET['projekt'];
$room = $_GET['room'];
$divice = $_GET['device'];

try {
  $connection = new mysqli('localhost', 'root', '', 'airmonitoring');
} catch (Exception $e) {
  die('MySQL-Verbindung fehlgeschlagen: ' . $e->getMessage());
}


$sql = "INSERT INTO tbl_data (temp_value, hum_value, pres_value, co2_value, room, device) VALUES ($temperature, $humidity, $pressure, $co2, $room, $device)";
  
try {
  $connection->query($sql);
  echo "Neuer Datensatz erfolgreich erstellt";
} catch (Exception $e) {
  die('Fehler beim Einfügen des Datensatzes: ' . $connection->error);
}
  
  
$connection->close();
  
?>

