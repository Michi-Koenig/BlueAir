<?php
function tageImMonatArray($date) {
    $numDays = date('t', strtotime($date));
    $daysArray = [];

    for ($i = 1; $i <= $numDays; $i++) {
        array_push($daysArray, $i);
    }

    return $daysArray;
}

// Beispiel für die Verwendung der Funktion
$date = "2024-05-14"; // YYYY-MM-DD
$daysArray = tageImMonatArray($date);

print_r($daysArray);
?>
