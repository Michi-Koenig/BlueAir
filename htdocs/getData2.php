<?php
//Datenbank Variabeln
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "airmonitoring";

//Abfrage Variabeln
$project = "Holderbaum";
$room = "schlafen";

// Erstellen Sie eine Verbindung
$conn = new mysqli($servername, $username, $password, $dbname);

// Überprüfen Sie die Verbindung
if ($conn->connect_error) {
  die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

for($i=0; $i < 24; $i++){

  $sql = "SELECT AVG(temp_value) AS temp, AVG(hum_value) AS hum, AVG(pres_value) AS pres, AVG(co2_value) AS co2 FROM $project WHERE HOUR(Zeit) = $i AND raum = 'schlafen' ";
  $result = $conn->query($sql);
  echo $i." :";
  if ($result->num_rows > 0) {
    // Daten von jedem Datensatz ausgeben
    while($row = $result->fetch_assoc()) {
      echo "Temperatur: " .$row["temp"]. ", Feuchte: ".$row["hum"]. ", Luftdruck: ".$row["pres"]. ", Co2: ".$row["co2"]. "<br>";
    }
  } else {
    echo "0 Ergebnisse";
  }

}


$conn->close();
?>