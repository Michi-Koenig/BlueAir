<?php

// Datenbankvariablen
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "airmonitoring";

$project = $_GET['project'];
$tempMax = $_GET['tempMax'];
$tempMin = $GET['tempMin'];
$humMax = $GET['humMax'];
$humMin = $GET['humMin'];
$presMax = $GET['presMax'];
$presMin = $GET['presMin'];
$co2Max = $GET['co2Max'];

// Erstelle eine Verbindung
$conn = new mysqli($servername, $username, $password, $dbname);

// Überprüfe die Verbindung
if ($conn->connect_error) {
  die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

//__________________________________________________________________________________
// SQL-Abfrage ob die Max Temperatur überschriten worden ist
$sql = 'WITH RankedTemps AS (
    SELECT *,
           LAG(temp_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS prev_value,
           LEAD(temp_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS next_value
    FROM ' . $project . '
),
Filtered AS (
    SELECT *,
           CASE
               WHEN temp_value > '.$tempMax.' AND prev_value <= '.$tempMax.' AND next_value > '.$tempMax.' THEN 1
               ELSE 0
           END AS is_high
    FROM RankedTemps
)
SELECT *
FROM (
    SELECT zeitstempel, temp_value, room, device
    FROM Filtered
    WHERE is_high = 1
) AS Sequences;';

// Führe die Abfrage aus
$result = $conn->query($sql);

// Ergebnis in ein Array speichern
$dataTempMax = array();
if ($result->num_rows > 0) {
    // Ausgabe der Daten jedes Zeile
    while($row = $result->fetch_assoc()) {
        $dataTempMax[] = $row;
    }
}
//__________________________________________________________________________________
// SQL-Abfrage ob die Min Temperatur unterschriten worden ist
$sql = 'WITH RankedTemps AS (
    SELECT *,
           LAG(temp_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS prev_value,
           LEAD(temp_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS next_value
    FROM ' . $project . '
),
Filtered AS (
    SELECT *,
           CASE
               WHEN temp_value < '.$tempMin.' AND prev_value >= '.$tempMin.' AND next_value < '.$tempMin.' THEN 1
               ELSE 0
           END AS is_high
    FROM RankedTemps
)
SELECT *
FROM (
    SELECT zeitstempel, temp_value, room, device
    FROM Filtered
    WHERE is_high = 1
) AS Sequences;';

// Führe die Abfrage aus
$result = $conn->query($sql);

// Ergebnis in ein Array speichern
$dataTempMin = array();
if ($result->num_rows > 0) {
    // Ausgabe der Daten jedes Zeile
    while($row = $result->fetch_assoc()) {
        $dataTempMin[] = $row;
    }
}
//__________________________________________________________________________________
// SQL-Abfrage ob die Max Feuchte überschriten worden ist
$sql = 'WITH RankedTemps AS (
    SELECT *,
           LAG(hum_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS prev_value,
           LEAD(hum_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS next_value
    FROM ' . $project . '
),
Filtered AS (
    SELECT *,
           CASE
               WHEN hum_value > '.$humMax.' AND prev_value <= '.$humMax.' AND next_value > '.$humMax.' THEN 1
               ELSE 0
           END AS is_high
    FROM RankedTemps
)
SELECT *
FROM (
    SELECT zeitstempel, hum_value, room, device
    FROM Filtered
    WHERE is_high = 1
) AS Sequences;';

// Führe die Abfrage aus
$result = $conn->query($sql);

// Ergebnis in ein Array speichern
$dataHumMax = array();
if ($result->num_rows > 0) {
    // Ausgabe der Daten jedes Zeile
    while($row = $result->fetch_assoc()) {
        $dataHumMax[] = $row;
    }
}
//__________________________________________________________________________________
// SQL-Abfrage ob die Min Feuchte unterschriten worden ist
$sql = 'WITH RankedTemps AS (
    SELECT *,
           LAG(hum_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS prev_value,
           LEAD(hum_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS next_value
    FROM ' . $project . '
),
Filtered AS (
    SELECT *,
           CASE
               WHEN hum_value < '.$humMin.' AND prev_value >= '.$humMin.' AND next_value < '.$humMin.' THEN 1
               ELSE 0
           END AS is_high
    FROM RankedTemps
)
SELECT *
FROM (
    SELECT zeitstempel, hum_value, room, device
    FROM Filtered
    WHERE is_high = 1
) AS Sequences;';

// Führe die Abfrage aus
$result = $conn->query($sql);

// Ergebnis in ein Array speichern
$dataHumMin = array();
if ($result->num_rows > 0) {
    // Ausgabe der Daten jedes Zeile
    while($row = $result->fetch_assoc()) {
        $dataHumMin[] = $row;
    }
}
//__________________________________________________________________________________
// SQL-Abfrage ob die Max Druck überschriten worden ist
$sql = 'WITH RankedTemps AS (
    SELECT *,
           LAG(pres_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS prev_value,
           LEAD(pres_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS next_value
    FROM ' . $project . '
),
Filtered AS (
    SELECT *,
           CASE
               WHEN pres_value > '.$presMax.' AND prev_value <= '.$presMax.' AND next_value > '.$presMax.' THEN 1
               ELSE 0
           END AS is_high
    FROM RankedTemps
)
SELECT *
FROM (
    SELECT zeitstempel, pres_value, room, device
    FROM Filtered
    WHERE is_high = 1
) AS Sequences;';

// Führe die Abfrage aus
$result = $conn->query($sql);

// Ergebnis in ein Array speichern
$dataPressMax = array();
if ($result->num_rows > 0) {
    // Ausgabe der Daten jedes Zeile
    while($row = $result->fetch_assoc()) {
        $dataPressMax[] = $row;
    }
}
//__________________________________________________________________________________
// SQL-Abfrage ob die Min Druck unterschriten worden ist
$sql = 'WITH RankedTemps AS (
    SELECT *,
           LAG(pres_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS prev_value,
           LEAD(pres_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS next_value
    FROM ' . $project . '
),
Filtered AS (
    SELECT *,
           CASE
               WHEN pres_value < '.$presMin.' AND prev_value >= '.$presMin.' AND next_value < '.$presMin.' THEN 1
               ELSE 0
           END AS is_high
    FROM RankedTemps
)
SELECT *
FROM (
    SELECT zeitstempel, pres_value, room, device
    FROM Filtered
    WHERE is_high = 1
) AS Sequences;';

// Führe die Abfrage aus
$result = $conn->query($sql);

// Ergebnis in ein Array speichern
$dataPresMin = array();
if ($result->num_rows > 0) {
    // Ausgabe der Daten jedes Zeile
    while($row = $result->fetch_assoc()) {
        $dataPresMin[] = $row;
    }
}
//__________________________________________________________________________________
// SQL-Abfrage ob die Max Co2 überschriten worden ist
$sql = 'WITH RankedTemps AS (
    SELECT *,
           LAG(co2_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS prev_value,
           LEAD(co2_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS next_value
    FROM ' . $project . '
),
Filtered AS (
    SELECT *,
           CASE
               WHEN co2_value < '.$co2Max.' AND prev_value >= '.$co2Max.' AND next_value < '.$co2Max.' THEN 1
               ELSE 0
           END AS is_high
    FROM RankedTemps
)
SELECT *
FROM (
    SELECT zeitstempel, co2_value, room, device
    FROM Filtered
    WHERE is_high = 1
) AS Sequences;';

// Führe die Abfrage aus
$result = $conn->query($sql);

// Ergebnis in ein Array speichern
$dataco2Max = array();
if ($result->num_rows > 0) {
    // Ausgabe der Daten jedes Zeile
    while($row = $result->fetch_assoc()) {
        $dataco2Max[] = $row;
    }
}
//__________________________________________________________________________________


// Verbindung schließen
$conn->close();

// Erzeuge das JSON Objekt
$jsonObject = array(
    "projects" => $resultArray,
    "temp-Max" => $dataTempMax,
    "temp-Min" => $dataTempMin,
    "hum-Max" => $dataHumMax,
    "hum-Min" => $dataHumMin,
    "pres-Max" => $dataPresMax,
    "pres-Min" => $dataPresMin,
    "co2-Max" => $dataco2Max
);

//Gib das JSON Objekt aus
echo json_encode($jsonObject, JSON_PRETTY_PRINT);
?>