#include <Wire.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BME280.h>
#include <WiFi.h>
#include <HTTPClient.h>

Adafruit_BME280 bme; // I2C
const char WIFI_SSID[] = "UPC2128142";
const char WIFI_PASSWORD[] = "Koenig-2022";

String HOST_NAME = "http://192.168.0.241"; // change to your PC's IP address
String PATH_NAME   = "/ESP32/insert_temp.php";

void setup() {
  Serial.begin(9600);
  pinMode(5, INPUT);  // PWM input CO2-Sensor
  
  // WIFI Verbindungsaufabu
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  // Statusabfrage BME280
  bool status;
  status = bme.begin(0x76);  
  if (!status) {
    Serial.println("Could not find a valid BME280 sensor, check wiring!");
    while (1);
  }
}

void loop() { 
  
  //Messwerte einlesen und in Stringvariabeln speichern
  float temp = bmeTempValue();
  float hum = bmeHumValue();
  float pres = bmePresValue();
  int c = bmeCo2Value();
  String temperatue = "temperature=" + String(temp);
  String humidity = "humidity=" + String(hum);
  String pressure = "pressure=" + String(pres);
  String co2 = "co2=" + String(c);
  
  HTTPClient http;    //Objekt erstellung http-Client

  http.begin(HOST_NAME + PATH_NAME + "?" + temperatue + "&" + humidity + "&" + pressure + "&" + co2); //HTTP
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

  delay(59000);
}

float bmeTempValue(){
  float temp=bme.readTemperature();
  return temp;
}
float bmeHumValue(){
  float humidity=bme.readHumidity();
  return humidity;
}
float bmePresValue(){
  float pressure=bme.readPressure() / 100.2F;
  return pressure;
}
int bmeCo2Value(){

  int Messbereich = 2000; // Der voreingestellte Messbereich (0-2000ppm). Der Sensor MH-Z19B kann auch auf einen Maximalwert von 2000ppm vorkonfiguriert sein.
  unsigned long ZeitMikrosekunden; // Variable für die Dauer des PWM-Signalpegels in Mikrosenkunden
  unsigned long ZeitMillisekunden; // Variable für die Dauer des PWM-Signalpegels in Millisekunden
  
  ZeitMikrosekunden = pulseIn(5, HIGH, 2000000); // Der pulseIn Befehl misst die Zeit, ab der ein Signal am angegebenen Pin auf HIGH wechselt und in diesem Zustand verbleibt. Standartmäßig endet diese Messung nach maximal 1.000.000 Mikrosekunden (1000ms). Durch das Ahängen des letzten Wertes kann man diesen sogenannten "Timeout" verlängern. Da das Signal des CO2 Sensors bis zu 1004ms lang sein kann, müssen wir den Wert entsprechend hoch ansetzen.
  ZeitMillisekunden = ZeitMikrosekunden/1000; // Umwandeln der Zeiteinheit von Mikrosekunden in Millisekunden.
  float Prozent = ZeitMillisekunden / 1004.0; // Die maximale Länge des PWM-Signals ist laut Datenblatt des MH-Z19B 1004ms (Millisekunden) lang. Daher berechnen wir hier die gemessene PWM-Signaldauer durch die maximal mögliche Signaldauer und erhalten einen Prozentwert des aktiven (5V) Pegels im PWM-Signal. Dieser Prozentwert spiegelt einen PPM-Wert zwischen 0PPM und 5000PPM wieder.
  int PPM = Messbereich * Prozent; // PPM-Wert berechnen aus der prozentualen Signaldauer und dem maximalen Messbereich.

  return PPM;
}