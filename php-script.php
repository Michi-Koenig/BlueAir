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

<?php
// Verbindungsparameter
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database";

// Erstellen Sie eine Verbindung
$conn = new mysqli($servername, $username, $password, $dbname);

// Überprüfen Sie die Verbindung
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Datum als Parameter übergeben
$date = $_GET['date'];

// Start- und Enddatum der Woche berechnen
$start_date = date('Y-m-d', strtotime('last Monday', strtotime($date)));
$end_date = date('Y-m-d', strtotime('next Sunday', strtotime($date)));

// SQL-Abfrage
$sql = "SELECT DAYNAME(timestamp) as weekday, AVG(temperature) as avg_temperature, AVG(humidity) as avg_humidity
        FROM your_table
        WHERE DATE(timestamp) BETWEEN '$start_date' AND '$end_date'
        GROUP BY DAYOFWEEK(timestamp)";

$result = $conn->query($sql);

$averages = array();

if ($result->num_rows > 0) {
    // Daten von jedem Rekord holen
    while($row = $result->fetch_assoc()) {
        $averages[$row["weekday"]] = array("avg_temperature" => $row["avg_temperature"], "avg_humidity" => $row["avg_humidity"]);
    }
} else {
    echo "Keine Ergebnisse";
}

$conn->close();

// Durchschnittswerte ausgeben
print_r($averages);
?>
