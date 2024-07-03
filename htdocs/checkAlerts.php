<?php

// Datenbankvariablen
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "airmonitoring";

$project = $_GET['project'];
$tempMax = $_GET['tempMax'];
$tempMin = $_GET['tempMin'];
$humMax = $_GET['humMax'];
$humMin = $_GET['humMin'];
$presMax = $_GET['presMax'];
$presMin = $_GET['presMin'];
$co2Max = $_GET['co2Max'];

// Erstelle eine Verbindung
$conn = new mysqli($servername, $username, $password, $dbname);

// Überprüfe die Verbindung
if ($conn->connect_error) {
  die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

$sql = 'WITH RankedTemps AS (
    SELECT *,
           LAG(temp_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS prev_value,
           LEAD(temp_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS next_value,
           ROW_NUMBER() OVER (PARTITION BY room, device ORDER BY zeitstempel DESC) AS row_num
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
    SELECT Date(zeitstempel),Time(zeitstempel), room, device, temp_value, hum_value, pres_value, co2_value,
            CASE WHEN row_num = 1 THEN 1 ELSE 0 END AS is_last_record
    FROM Filtered
    WHERE is_high = 1
) AS Sequences;';

$sql .= 'WITH RankedTemps AS (
    SELECT *,
           LAG(temp_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS prev_value,
           LEAD(temp_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS next_value,
           ROW_NUMBER() OVER (PARTITION BY room, device ORDER BY zeitstempel DESC) AS row_num
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
    SELECT Date(zeitstempel),Time(zeitstempel), room, device, temp_value, hum_value, pres_value, co2_value,
            CASE WHEN row_num = 1 THEN 1 ELSE 0 END AS is_last_record
    FROM Filtered
    WHERE is_high = 1
) AS Sequences;';

$sql .= 'WITH RankedTemps AS (
    SELECT *,
           LAG(hum_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS prev_value,
           LEAD(hum_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS next_value,
           ROW_NUMBER() OVER (PARTITION BY room, device ORDER BY zeitstempel DESC) AS row_num
    FROM ' . $project . '
),
Filtered AS (
    SELECT *,
           CASE
               WHEN hum_value > '.$humMax.' AND prev_value > '.$humMax.' AND (next_value <= '.$humMax.' OR next_value IS NULL) THEN 1
               ELSE 0
           END AS is_high
    FROM RankedTemps
)
SELECT *
FROM (
    SELECT Date(zeitstempel),Time(zeitstempel), room, device, temp_value, hum_value, pres_value, co2_value,
           CASE WHEN row_num = 1 THEN 1 ELSE 0 END AS is_last_record
    FROM Filtered
    WHERE is_high = 1
) AS Sequences;';

$sql .= 'WITH RankedTemps AS (
    SELECT *,
           LAG(hum_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS prev_value,
           LEAD(hum_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS next_value,
           ROW_NUMBER() OVER (PARTITION BY room, device ORDER BY zeitstempel DESC) AS row_num
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
    SELECT Date(zeitstempel),Time(zeitstempel), room, device, temp_value, hum_value, pres_value, co2_value,
            CASE WHEN row_num = 1 THEN 1 ELSE 0 END AS is_last_record
    FROM Filtered
    WHERE is_high = 1
) AS Sequences;';

$sql .= 'WITH RankedTemps AS (
    SELECT *,
           LAG(pres_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS prev_value,
           LEAD(pres_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS next_value,
           ROW_NUMBER() OVER (PARTITION BY room, device ORDER BY zeitstempel DESC) AS row_num
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
    SELECT Date(zeitstempel),Time(zeitstempel), room, device, temp_value, hum_value, pres_value, co2_value,
            CASE WHEN row_num = 1 THEN 1 ELSE 0 END AS is_last_record
    FROM Filtered
    WHERE is_high = 1
) AS Sequences;';

$sql .= 'WITH RankedTemps AS (
    SELECT *,
           LAG(pres_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS prev_value,
           LEAD(pres_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS next_value,
           ROW_NUMBER() OVER (PARTITION BY room, device ORDER BY zeitstempel DESC) AS row_num
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
    SELECT Date(zeitstempel),Time(zeitstempel), room, device, temp_value, hum_value, pres_value, co2_value,
            CASE WHEN row_num = 1 THEN 1 ELSE 0 END AS is_last_record
    FROM Filtered
    WHERE is_high = 1
) AS Sequences;';

$sql .= 'WITH RankedTemps AS (
    SELECT *,
           LAG(co2_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS prev_value,
           LEAD(co2_value) OVER (PARTITION BY room, device ORDER BY zeitstempel) AS next_value,
           ROW_NUMBER() OVER (PARTITION BY room, device ORDER BY zeitstempel DESC) AS row_num
    FROM ' . $project . '
),
Filtered AS (
    SELECT *,
           CASE
               WHEN co2_value > '.$co2Max.' AND prev_value > '.$co2Max.' AND (next_value <= '.$co2Max.' OR next_value IS NULL) THEN 1
               ELSE 0
           END AS is_high
    FROM RankedTemps
)
SELECT *
FROM (
    SELECT Date(zeitstempel),Time(zeitstempel), room, device, temp_value, hum_value, pres_value, co2_value,
           CASE WHEN row_num = 1 THEN 1 ELSE 0 END AS is_last_record
    FROM Filtered
    WHERE is_high = 1
) AS Sequences;';

$i = 0;
$alerts = [$dataTempMax = array(), $dataTempMin = array(), $dataHumMax = array(), $dataHumMin = array(), $dataPresMax = array(), $dataPresMin = array(), $dataco2Max = array()];

if ($conn->multi_query($sql)) {
    do {
        // Speichere das erste Ergebnis-Set
        if ($result = $conn->store_result()) {
            
            while ($row = $result->fetch_assoc()) {
                $alerts[$i][] = $row;
            }
            $result->free_result();
        }
        // Wenn es weitere Ergebnis-Sets gibt, erhöhe i
        if ($conn->more_results()) {
            $i++;
        } else {
            break;
        }
        // Bereite das nächste Ergebnis-Set vor
    } while ($conn->next_result());
}

// Verbindung schließen
$conn->close();

// Erzeuge das JSON Objekt
$jsonObject = array(
    "project" => $project,
    "tempMax" => $alerts[0],
    "tempMin" => $alerts[1],
    "humMax" => $alerts[2],
    "humMin" => $alerts[3],
    "presMax" => $alerts[4],
    "presMin" => $alerts[5],
    "co2Max" => $alerts[6]
);

//Gib das JSON Objekt aus
echo json_encode($jsonObject, JSON_PRETTY_PRINT);
?>