<?php
// Verbindung zur Datenbank herstellen (ersetze die Platzhalter mit deinen eigenen Daten)
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "airmonitoring";

// Verbindung aufbauen
$conn = new mysqli($servername, $username, $password, $dbname);

// Überprüfen, ob die Verbindung erfolgreich war
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Tabelle erstellen, falls sie noch nicht existiert
$sql = "CREATE TABLE IF NOT EXISTS Baumschulenweg (
    zeitstempel TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ID INT NOT NULL AUTO_INCREMENT,
    temp_value FLOAT NOT NULL,
    hum_value FLOAT NOT NULL,
    pres_value FLOAT NOT NULL,
    co2_value INT NOT NULL,
    room VARCHAR(30) NOT NULL,
    device INT NOT NULL,
    PRIMARY KEY (ID)
) ENGINE = InnoDB";

if ($conn->query($sql) === TRUE) {
    echo "Tabelle Messdaten wurde erfolgreich erstellt oder existiert bereits.<br>";
} else {
    echo "Fehler beim Erstellen der Tabelle: " . $conn->error;
}

// Zufällige Messdaten generieren und in die Tabelle einfügen
for ($i = 0; $i < 20000; $i++) {
    $minutes = $i*5;
    $zeitstempel = date("Y-m-d H:i:s", strtotime("+ ".$minutes." minutes"));
    $temp = rand(15, 25); 
    $hum = rand(35, 60); 
    $pres = rand(956, 987); 
    $co2 = rand(200, 600);
    $room = "schlafzimmer";
    $device = 1;

    $sql = "INSERT INTO Baumschulenweg (zeitstempel, temp_value, hum_value, pres_value, co2_value, room, device) VALUES ('$zeitstempel', $temp, $hum, $pres, $co2, '$room', $device )";

    if ($conn->query($sql) === TRUE) {
        echo "Datensatz eingefügt: Zeitstempel = $zeitstempel, Temperatur = $temp<br>";
    } else {
        echo "Fehler beim Einfügen des Datensatzes: " . $conn->error;
    }
}

// Verbindung schließen
$conn->close();

?>