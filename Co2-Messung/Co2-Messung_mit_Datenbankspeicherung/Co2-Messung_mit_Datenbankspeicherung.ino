#include <WiFi.h>
#include <HTTPClient.h>

const char WIFI_SSID[] = "UPC2128142";
const char WIFI_PASSWORD[] = "Koenig-2022";

String HOST_NAME = "http://192.168.0.241"; // change to your PC's IP address
String PATH_NAME   = "/ESP32/insert_temp.php";
String queryString;

//__________________________________________________________________________

int SensorPin = 5; // Der PWM-Pin des Sensors wird an Pin5 des Mikrocontrollers angeschlossen. Bei anderen Mikrocontrollern muss darauf geachtet werden, dass der Pin PWM-Fähig ist.
int Messbereich = 2000; // Der voreingestellte Messbereich (0-5000ppm). Der Sensor MH-Z19B kann auch auf einen Maximalwert von 2000ppm vorkonfiguriert sein.
unsigned long ZeitMikrosekunden; // Variable für die Dauer des PWM-Signalpegels in Mikrosenkunden
unsigned long ZeitMillisekunden; // Variable für die Dauer des PWM-Signalpegels in Millisekunden

int PPM = 0; // Variable für den CO2-Messwert in ppm (parts per million - Anteile pro Million)
float Prozent=0; // Variable für den prozentuale Länge des PWM-Signals


void setup() {
  pinMode(SensorPin, INPUT); //Der Pin für die Sensorwerte (6) wird als Eingang definiert.
  Serial.begin(9600); // Aufbau der seriellen Verbindung, um Messwerte am Seriellen Monitor anzeigen zu können

 WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
  
  
}

void loop() {

  ZeitMikrosekunden = pulseIn(SensorPin, HIGH, 2000000); // Der pulseIn Befehl misst die Zeit, ab der ein Signal am angegebenen Pin auf HIGH wechselt und in diesem Zustand verbleibt. Standartmäßig endet diese Messung nach maximal 1.000.000 Mikrosekunden (1000ms). Durch das Ahängen des letzten Wertes kann man diesen sogenannten "Timeout" verlängern. Da das Signal des CO2 Sensors bis zu 1004ms lang sein kann, müssen wir den Wert entsprechend hoch ansetzen.
  ZeitMillisekunden = ZeitMikrosekunden/1000; // Umwandeln der Zeiteinheit von Mikrosekunden in Millisekunden.
  float Prozent = ZeitMillisekunden / 1004.0; // Die maximale Länge des PWM-Signals ist laut Datenblatt des MH-Z19B 1004ms (Millisekunden) lang. Daher berechnen wir hier die gemessene PWM-Signaldauer durch die maximal mögliche Signaldauer und erhalten einen Prozentwert des aktiven (5V) Pegels im PWM-Signal. Dieser Prozentwert spiegelt einen PPM-Wert zwischen 0PPM und 5000PPM wieder.
  PPM = Messbereich * Prozent; // PPM-Wert berechnen aus der prozentualen Signaldauer und dem maximalen Messbereich.
  queryString = "?temperature="+String(PPM);

  HTTPClient http;

  http.begin(HOST_NAME + PATH_NAME + queryString); //HTTP
  int httpCode = http.GET();

  // httpCode will be negative on error
  if(httpCode > 0) {
    // file found at server
    if(httpCode == HTTP_CODE_OK) {
      String payload = http.getString();
      Serial.println(payload);
    } else {
      // HTTP header has been send and Server response header has been handled
      Serial.printf("[HTTP] GET... code: %d\n", httpCode);
    }
  } else {
    Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
  }

  http.end();
  Serial.println("------------------------------");
  Serial.println(HOST_NAME + PATH_NAME + queryString);
  Serial.println(queryString);
  Serial.println("------------------------------");
  Serial.print("CO2 Anteil in der Luft in PPM: "); // Ausgabe der Werte über den Seriellen Monitor
  Serial.println(PPM);
  delay(100000);
}
