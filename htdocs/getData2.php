<?php
//Datenbank Variabeln
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "airmonitoring";

//Abfrage Variabeln
$project = "Holderbaum";
$room = "schlafen";
$typOfTime = "d";
$year = 2023;
$month = 12;
$day = 31;

$date = mktime(0, 0, 0, $month, $day, $year);
$datum = date("d.m.Y H:i", $date);

echo $datum. "<br>";
echo "Wochentag: ".date("w", $date). "<br>";
echo "<br><br>";

// Erstellen Sie eine Verbindung
$conn = new mysqli($servername, $username, $password, $dbname);

// Überprüfen Sie die Verbindung
if ($conn->connect_error) {
  die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

$arrayData;

$sql = "SELECT AVG(temp_value) AS temp, AVG(hum_value) AS hum, AVG(pres_value) AS pres, AVG(co2_value) AS co2, HOUR(Zeit) as hour, geraet FROM $project WHERE raum = '$room' AND YEAR(Zeit) = $year AND MONTH(Zeit) = $month AND DAY(Zeit) = $day  GROUP BY HOUR(Zeit), geraet ORDER BY geraet, HOUR(Zeit) ";
$result = $conn->query($sql);
  
if ($result->num_rows > 0) {
  // Daten von jedem Datensatz ausgeben
  while($row = $result->fetch_assoc()) {

    echo "Hour: " .$row["hour"]. " Temperatur: " .$row["temp"]. ", Feuchte: ".$row["hum"]. ", Luftdruck: ".$row["pres"]. ", Co2: ".$row["co2"]. ", Gerät: " .$row['geraet']. "<br>";
    
    for($i=0; $i < 24; $i++){
      if( $row["hour"] == $i){
        $arrayData[0][$i] = $row["temp"];
        $arrayData[1][$i] = $row["hum"];
        $arrayData[2][$i] = $row["pres"];
        $arrayData[3][$i] = $row["co2"];
      }
      
    }
  }
} else {
  echo "0 Ergebnisse";
}


$conn->close();
?>