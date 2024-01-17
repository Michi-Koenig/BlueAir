/*
   Hello_World.ino
   Henry Abrahamsen
   8/12/23
   Simple code using the basic features of Bluetooth Serial
   Details available at https://docs.henhen1227.com/
*/

#include <BluetoothSerial.h>

BluetoothSerial SerialBT;

String str;
String SSID;
String passwort;
String haushalt;
String raum;
String geraet;

void setup() {
  // Start communication with bluetooth device
  SerialBT.begin("ESP32Config");
  Serial.begin(9600);

  Serial.println("Setup Complete");
}

void loop() {
  if(SerialBT.available()){
    str = SerialBT.readString();
    Serial.println(str);

    int str_len = str.length() + 1; 
    char charArray[str_len];
    str.toCharArray(charArray, str_len);
    char *token;

    token = strtok(charArray, ",");
    SSID = token;
    token = strtok(NULL, ",");
    passwort = token;
    token = strtok(NULL, ",");
    haushalt = token;
    token = strtok(NULL, ",");
    raum = token;
    token = strtok(NULL, ",");
    geraet = token;

    Serial.println("WLan SSID: "+SSID);
    Serial.println("Passwort:  "+passwort);
    Serial.println("Haushalt:  "+haushalt);
    Serial.println("Raum:      "+raum);
    Serial.println("Gerät:     "+geraet);
  }
}



