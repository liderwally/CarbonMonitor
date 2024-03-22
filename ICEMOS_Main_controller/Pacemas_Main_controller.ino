#include <SoftwareSerial.h>
#include <TinyGPS.h>

int sensorValue,sensorRealValue;
int greenLED = 11;
int redLED = 12;
int sensorPin = A0;
const String indId = "5";
int state = 0;
const int pin = 6;
String separ  = "<";
float gpslat= -6.792354, gpslon= 39.208328, age;

TinyGPS gps;
SoftwareSerial sgps( 4 , 5 );
SoftwareSerial com( 8 , 9 );


void setup()
{
  Serial.begin(9600); // sets the serial port to 9600
  com.begin(9600);
  pinMode(digitalRead(pin), OUTPUT);
  pinMode(greenLED, OUTPUT);
  pinMode(redLED, OUTPUT);
  pinMode(sensorPin, INPUT);
  sgps.begin(9600);
}

void loop(){
 sensorValue = analogRead(sensorPin);       // read analog input pin A5
 sensorRealValue =map(sensorValue,0,1023,0,200);
 Serial.print("Carbondioxide Concetration: ");
  Serial.println(sensorRealValue);
String location;  

  if (sensorRealValue > 75) {
    digitalWrite(greenLED, LOW);
    digitalWrite(digitalRead(pin), HIGH);
    digitalWrite(redLED, HIGH);
    Serial.println("Alert!!!");
    state=1;
    delay(2000); // wait 2000ms
  }
  else {
    digitalWrite(greenLED, HIGH);
    digitalWrite(redLED, LOW);
    digitalWrite(digitalRead(pin), LOW);
    Serial.println(F("Normal"));
    delay(500); // wait 500ms
    }
 

Serial.println("reaching here..");

while (sgps.available()){
  char c = sgps.read();
    location =""+c;
    Serial.print(location);
    if (gps.encode(c)){
      gps.f_get_position(&gpslat, &gpslon);
    }
}

Serial.print("Current latitude :");
Serial.println(gpslat,DEC);
Serial.print("Current longitude :");
Serial.println(gpslon,DEC);
    if (sensorRealValue > 75) {
      state = 1;
      String genData = indId + separ + String(gpslat) + separ + String(gpslon) + separ + String(state) + separ + String(sensorRealValue)+ separ + "=";
      com.print(genData);
      
      state = 0;
      }
    else if(sensorRealValue <75) {
      state  = 0;
      String genData = indId + separ + String(gpslat) + separ + String(gpslon) +  separ + String(state) + separ + String(sensorRealValue) + separ +"=";
      com.print(genData);    
      state = 0;
    }
      delay(100);
      if(millis()%5000 >4200){
        Serial.println(millis()%20000);
        String genData = indId + separ + String(gpslat) + separ + String(gpslon) + separ + String(state) + separ + String(sensorRealValue) + separ + "=";
        Serial.print("gendata :");
        Serial.println(genData);
        }
  }
