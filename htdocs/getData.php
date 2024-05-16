<?php

$typOfTime = "day";             // $_GET['typOfTime'];
$timeperiod = "2024-06-15";     // $_GET['timeperiod'];
$project = $_GET['project'];
$room = $_GET['room'];

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

// SQL-Abfrage wie viele geräte befinden sich im Raum
$sql = "SELECT DISTINCT device FROM " .$project . " WHERE room = '".$room."'";

// Führe die Abfrage aus
$resultdevice = $conn->query($sql);

// zählen wie viele Werte liefert das Ergebnis
$devices = $resultdevice->num_rows;

//__________________________________________________________________________________

$arrayData = array();

if ($typOfTime == "day"){
    $arrayTime = array(0,2,4,6,8,10,12,14,16,18,20,22);

    for ($i = 1; $i <= $devices; $i++) {
    
        // SQL-Abfrage Tag
        $sql = "SELECT AVG(temp_value) as avg_temperature, AVG(hum_value) as avg_humidity, AVG(pres_value) as avg_pressure, AVG(co2_value) as avg_co2
                FROM $project
                WHERE DATE(zeitstempel) BETWEEN '$timeperiod' AND '$timeperiod' AND device = $i AND room = '$room'
                GROUP BY HOUR(zeitstempel)";
    
        $resultday = $conn->query($sql);

        // erstellen der Arrays für die vier Charts
        $arraytemp = array();
        $arrayhum = array();
        $arraypres = array();
        $arrayco2 = array();

        //Daten von jedem Datensatz in das jeweilige Array speichern
        if ($resultday->num_rows > 0) {
            
            while($row = $resultday->fetch_assoc()) {
                
                array_push($arraytemp, number_format($row["avg_temperature"],1));
                array_push($arrayhum, number_format($row["avg_humidity"]));
                array_push($arraypres, number_format($row["avg_pressure"]));
                array_push($arrayco2, number_format($row["avg_co2"]));

            }
        } else {
            echo "Keine Ergebnisse";
        }

        // Mittelwert berechnen von 12 Stundenpaaren
        $arraytempAVG = averageValue($arraytemp);
        $arrayhumAVG = averageValue($arrayhum);
        $arraypresAVG = averageValue($arraypres);
        $arrayco2AVG = averageValue($arrayco2);

        // Messwertarrays in arrayData zusammenfassen
        array_push($arrayData, $arraytempAVG, $arrayhumAVG, $arraypresAVG, $arrayco2AVG);
        
    }

}elseif($typOfTime == "week"){

    $arrayTime = array("MO", "DI", "MI", "DO", "FR", "SA", "SO");

    // Start- und Enddatum der Woche berechnen
    $start_date = date('Y-m-d', strtotime('last Monday', strtotime($timeperiod)));
    $end_date = date('Y-m-d', strtotime('next Sunday', strtotime($timeperiod)));

    for ($i = 1; $i <= $devices; $i++) {
    
        // SQL-Abfrage für Woche
        $sql = "SELECT AVG(temp_value) as avg_temperature, AVG(hum_value) as avg_humidity, AVG(pres_value) as avg_pressure, AVG(co2_value) as avg_co2
                FROM $project
                WHERE DATE(zeitstempel) BETWEEN '$start_date' AND '$end_date' AND device = $i AND room = '$room'
                GROUP BY DAYOFWEEK(zeitstempel)";
        
        $resultweek = $conn->query($sql);

        // erstellen der Arrays für die vier Charts
        $arraytemp = array();
        $arrayhum = array();
        $arraypres = array();
        $arrayco2 = array();

        //Daten von jedem Datensatz in das jeweilige Array speichern
        if ($resultweek->num_rows > 0) {
           
            while($row = $resultweek->fetch_assoc()) {
                
                array_push($arraytemp, number_format($row["avg_temperature"],1));
                array_push($arrayhum, number_format($row["avg_humidity"]));
                array_push($arraypres, number_format($row["avg_pressure"]));
                array_push($arrayco2, number_format($row["avg_co2"]));

            }
        } else {
            echo "Keine Ergebnisse";
        }
        // Messwertarrays in arrayData zusammenfassen
        array_push($arrayData, $arraytemp, $arrayhum, $arraypres, $arrayco2);
    }

}elseif($typOfTime == "month"){

    // ermitteln wie viele Tage der aktuelle Monat hat und das arrayTime fortlaufend befüllen
    $numDays = date('t', strtotime($timeperiod));
    $arrayTime = [];

    for ($i = 1; $i <= $numDays; $i++) {
        array_push($arrayTime, $i);
    }

    // das t anstatt das d gibt die Anzahl der Tage im Monat eines bestimmten Datums zurück
    $start_date = date('Y-m-1', strtotime($timeperiod));
    $end_date = date('Y-m-t', strtotime($timeperiod));
    
    for ($i = 1; $i <= $devices; $i++) {
    
        // SQL-Abfrage für Monat
        $sql = "SELECT AVG(temp_value) as avg_temperature, AVG(hum_value) as avg_humidity, AVG(pres_value) as avg_pressure, AVG(co2_value) as avg_co2
                FROM $project
                WHERE DATE(zeitstempel) BETWEEN '$start_date' AND '$end_date' AND device = $i AND room = '$room'
                GROUP BY DAY(zeitstempel)";
        
        $resultmonth = $conn->query($sql);

        // erstellen der Arrays für die vier Charts
        $arraytemp = array();
        $arrayhum = array();
        $arraypres = array();
        $arrayco2 = array();

        //Daten von jedem Datensatz in das jeweilige Array speichern
        if ($resultmonth->num_rows > 0) {
            
            while($row = $resultmonth->fetch_assoc()) {
                
                array_push($arraytemp, number_format($row["avg_temperature"],1));
                array_push($arrayhum, number_format($row["avg_humidity"]));
                array_push($arraypres, number_format($row["avg_pressure"]));
                array_push($arrayco2, number_format($row["avg_co2"]));

            }
        } else {
            echo "Keine Ergebnisse";
        }
        // Messwertarrays in arrayData zusammenfassen
        array_push($arrayData, $arraytemp, $arrayhum, $arraypres, $arrayco2);
        
    }

}

// Verbindung schließen
$conn->close();

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

function averageValue($array){

    // Neues Array für die Durchschnittswerte
    $averageArray = [];

    // Schleife, die durch das ursprüngliche Array geht
    for ($i = 0; $i < count($array); $i += 2) {
        // Berechne den Durchschnitt der aktuellen beiden Werte
        $average = ($array[$i] + $array[$i + 1]) / 2;
        
        // Füge den Durchschnittswert zum neuen Array hinzu
        array_push($averageArray, $average);
        
    }

    return $averageArray;
    
}

?>
