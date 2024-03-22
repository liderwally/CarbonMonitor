#include <ESP8266WiFi.h>
#include <SoftwareSerial.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>

#define ledBuiltin D2

const char* ssid = "Catheryn's galaxy";  // Your wifi Name
const char* password = "catherine";      // Your wifi Password
String serverPath = "192.168.250.210";   //assumed starting address
// milliseconds, will quickly become a bigger number than can be stored in an int.
unsigned long lastTime = 0;
String serverName = "";
// Set timer to 1 seconds (1000)
unsigned long timerDelay = 1000;

//general variables
String dataRcvd, latitude = "0", longitude = "0", indId = "5", sensorValue = "0", state = "0";
String lastDataRcvd = " ";

SoftwareSerial SerialCom(D7, D8);
WiFiClient client;


void setup() {
  Serial.begin(115200);
  SerialCom.begin(9600);
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
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
  //Serial.println(serverPath + "/CO2-conc-dashboard/CO2-conc-dashboard/industry-list.php");
  digitalWrite(ledBuiltin, HIGH);
  delay(1000);
  digitalWrite(ledBuiltin, HIGH);

  if (SerialCom.available()) {
    SerialCom.setTimeout(1000);
    dataRcvd = SerialCom.readStringUntil('=');
    //Serial.print("received some data :");
    Serial.println(dataRcvd);
    if (dataRcvd.startsWith("\n")) {
      dataRcvd = dataRcvd.substring(2, dataRcvd.length());
    }
  }

  if (WiFi.status() == WL_CONNECTED) {
    //Serial.print(" Wifi status connected ");
    HTTPClient http;
    if (millis() % 5000 > 4200) {  //ensures that it has passed exact 5 seconds since the last data transfer
      String servingPath = "";
      servingPath = "http://" + serverPath + "/CO2-conc-dashboard/CO2-industry-device-API/data-from-industry.php?data=" + dataRcvd;
      Serial.println(servingPath);
      http.begin(client, servingPath.c_str());  // Send HTTP GET request
      int httpResponseCode = http.GET();
      if (httpResponseCode > 0) {
        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);
        String payload = http.getString();
        Serial.println(payload);
      } else {
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
