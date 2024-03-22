#include <ESP8266WiFi.h>
#include <SoftwareSerial.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include <SoftwareSerial.h>
#include <TinyGPS.h>

#define ledBuiltin 2

int sensorValue, sensorRealValue;
int greenLED = D7;
int redLED = D8;
int sensorPin = A0;
const String indId = "7";
int state = 0;
const int pin = 6;
String separ = "<";
float gpslat = -6.792354, gpslon = 39.208328, age;
const char* ssid = "Catheryn's galaxy";  // Your wifi Name
const char* password = "catherine";      // Your wifi Password
String serverPath = "192.168.250.210";   //assumed starting address
// milliseconds, will quickly become a bigger number than can be stored in an int.
unsigned long lastTime = 0;
String serverName = "";

unsigned long timerDelay = 1000;
//general variables
String dataRcvd, latitude = "0", longitude = "0", indId1 = "0", sensorValues = "0", states = "0";
String lastDataRcvd = " ";


WiFiClient client;

void setup() {
  Serial.begin(115200); 

  pinMode(greenLED, OUTPUT);
  pinMode(redLED, OUTPUT);

  WiFi.mode(WIFI_STA);

  Serial.println("Connecting");

  while (WiFi.waitForConnectResult() != WL_CONNECTED) {
    WiFi.begin(ssid, password);
    Serial.println("Retrying connection...");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

 // ip address query from the user/admin
  Serial.println("What is your ip address: ");
  while (!Serial.available() && millis() < 15000) {
    Serial.print(".");
  }
  serverPath = Serial.readStringUntil(':');
  Serial.print(serverPath);
  String serverName = "http://";
  serverName = serverName + serverPath;
  serverName = serverName + "/CO2-conc-dashboard/industry-list.php";  //CO2-industry-device-API/data-from-industry.php";

  Serial.println("Timer set to 5 seconds (timerDelay variable), it will take 5 seconds before publishing the first reading.");
  pinMode(ledBuiltin, OUTPUT);
  Serial.print(serverName);
}

void loop() {

  sensorValue = analogRead(sensorPin);  // read analog input pin A5
  sensorRealValue = map(sensorValue, 0, 1023, 0, 200);
  Serial.print("Carbondioxide Concetration: ");
  Serial.println(sensorRealValue);
  String location;

  if (sensorRealValue > 75) {
    digitalWrite(greenLED, LOW);
    digitalWrite(digitalRead(pin), HIGH);
    digitalWrite(redLED, HIGH);
    Serial.println("Alert!!!");
    state = 1;
    delay(2000);  // wait 2000ms
  } else {
    digitalWrite(greenLED, HIGH);
    digitalWrite(redLED, LOW);
    digitalWrite(digitalRead(pin), LOW);
    Serial.println(F("Normal"));
    delay(500);  // wait 500ms
  }


  Serial.println("reaching here..");
  Serial.print("Current latitude :");
  Serial.println(gpslat, DEC);
  Serial.print("Current longitude :");
  Serial.println(gpslon, DEC);
  if (sensorRealValue > 75) {
    state = 1;
    String genData = indId + separ + String(gpslat) + separ + String(gpslon) + separ + String(state) + separ + String(sensorRealValue) + separ ;
    sendData(genData);
    state = 0;
  } else if (sensorRealValue < 75) {
    state = 0;
    String genData = indId + separ + String(gpslat) + separ + String(gpslon) + separ + String(state) + separ + String(sensorRealValue) + separ ;
    state = 0;
  }

  delay(100);

  if (millis() % 5000 > 4200) {
    Serial.println(millis() % 20000);
    String genData = indId + separ + String(gpslat) + separ + String(gpslon) + separ + String(state) + separ + String(sensorRealValue) + separ ;
    Serial.print("gendata :");
    Serial.println(genData);
    sendData(genData);

  }

  //Serial.println(serverPath + "/CO2-conc-dashboard/CO2-conc-dashboard/industry-list.php");
  digitalWrite(ledBuiltin, HIGH);
  delay(1000);
  digitalWrite(ledBuiltin, HIGH);
}

void sendData(String datarcvd) {

  if (WiFi.status() == WL_CONNECTED) {
    //Serial.print(" Wifi status connected ");
    HTTPClient http;
    if (millis() % 5000 > 4200) {  //ensures that it has passed exact 5 seconds since the last data transfer
      String servingPath = "";
      servingPath = "http://" + serverPath + "/CO2-conc-dashboard/CO2-industry-device-API/data-from-industry.php?data=" + datarcvd;
      Serial.println(servingPath);
      http.begin(client, servingPath.c_str());  // Send HTTP GET request
      int httpResponseCode = http.GET();
      if (httpResponseCode > 0) {
        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);
        String payload = http.getString();
        Serial.println(payload);
      } 
      else {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
      }
    }
    http.end();
  } else {
    Serial.println("WiFi Disconnected");
    delay(3000);
  }
}
