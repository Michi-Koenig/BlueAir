#include <Preferences.h>
#include <BluetoothSerial.h>

BluetoothSerial SerialBT;
Preferences appData;

String str;
String SSID;
String passwort;
String haushalt;
String raum;
String geraet;

void setup() {
  SerialBT.begin("ESP32Config");
  Serial.begin(115200);
  Serial.println();

  appData.begin("MIT-App", false);
  appData.clear();
  SSID = appData.getString("SSID","-");
  passwort = appData.getString("passwort","-");
  haushalt = appData.getString("haushalt","-");
  raum = appData.getString("raum","-");
  geraet = appData.getString("geraet","-");

  Serial.println("Das sind die gespeicherten Daten.");
  Serial.println("---------------------------------");
  Serial.println("WLan SSID: "+SSID);
  Serial.println("Passwort:  "+passwort);
  Serial.println("Haushalt:  "+haushalt);
  Serial.println("Raum:      "+raum);
  Serial.println("Gerät:     "+geraet);
  Serial.println();

  if( SSID.equals("-") || passwort.equals("-") || haushalt.equals("-") || raum.equals("-") || geraet.equals("-") ){

    while(!SerialBT.available()){}

    if(SerialBT.available()){
    str = SerialBT.readString();
    Serial.println(str);

    int str_len = str.length() + 1; 
    char charArray[str_len];
    str.toCharArray(charArray, str_len);
    char *token;

    token = strtok(charArray, ",");
    appData.putString("SSID", token);
    token = strtok(NULL, ",");
    appData.putString("passwort", token);
    token = strtok(NULL, ",");
    appData.putString("haushalt", token);
    token = strtok(NULL, ",");
    appData.putString("raum", token);
    token = strtok(NULL, ",");
    appData.putString("geraet", token);
    }
  }
}

void loop(){
  Serial.println("WLan SSID: "+appData.getString("SSID","-"));
  Serial.println("Passwort:  "+appData.getString("passwort","-"));
  Serial.println("Haushalt:  "+appData.getString("haushalt","-"));
  Serial.println("Raum:      "+appData.getString("raum","-"));
  Serial.println("Gerät:     "+appData.getString("geraet","-"));
  delay(5000);
}
