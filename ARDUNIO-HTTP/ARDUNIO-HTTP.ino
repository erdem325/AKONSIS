#include <SoftwareSerial.h>
#include <ArduinoJson.h>
#include <Wire.h>

SoftwareSerial mySerial(7, 8);

String apn = "internet";
String url = "http://erdemkivanc.com/arduino/index.php";
String data1;
String data2;

const int capacity = JSON_OBJECT_SIZE(20);
StaticJsonBuffer<capacity> jsonBuffer;
JsonObject& obj = jsonBuffer.createObject();
String parametre;

/* ULTRASONIC */
const int trigPin = 4;
const int echoPin = 5;

const int trig2Pin = 2;
const int echo2Pin = 3;

int maxMesafe = 35;

int sure = 0;
int uzaklik = 0;

int yuzde1 = 0;
int yuzde2 = 0;

int sure2 = 0;
int uzaklik2 = 0;

/* ULTRASONIC */

void setup() {
  Serial.begin(9600);
  pinMode(7, INPUT);
  pinMode(8, OUTPUT);
  mySerial.begin(9600);
  
  delay(10000);
  
  pinMode(trigPin, OUTPUT);
  pinMode(echoPin, INPUT);
  pinMode(trig2Pin, OUTPUT);
  pinMode(echo2Pin, INPUT);
  Serial.begin(9600);

  pinMode(LED_BUILTIN, OUTPUT);
}

void loop() {
    copKontrol();
    delay(20000);
}

void gsm_sendhttp() {  
  mySerial.println("AT");
  mySerial.println("AT+SAPBR=3,1,Contype,GPRS");
  mySerial.println("AT+SAPBR=3,1,APN," + "internet");
  mySerial.println("AT+SAPBR =1,1");
  mySerial.println("AT+SAPBR=2,1");
  mySerial.println("AT+HTTPINIT");
  mySerial.println("AT+HTTPPARA=CID,1");
  mySerial.println("AT+HTTPPARA=URL," + "http://erdemkivanc.com/arduino/index.php");
  mySerial.println("AT+HTTPPARA=CONTENT,application/x-www-form-urlencoded");
  mySerial.println("AT+HTTPDATA=192,10000");
  mySerial.println("params=" + parametre);
  mySerial.println("AT+HTTPACTION=1");
  mySerial.println("AT+HTTPREAD");
  mySerial.println("AT+HTTPTERM");
}

void runsl() {
  while (mySerial.available()) {
    Serial.write(mySerial.read());
  }
}

void copKontrol() {
  digitalWrite(trigPin, LOW); /* sensör pasif hale getirildi */
  delayMicroseconds(5);
  digitalWrite(trigPin, HIGH); /* Sensore ses dalgasının üretmesi için emir verildi */
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);  /* Yeni dalgaların üretilmemesi için trig pini LOW konumuna getirildi */ 
  sure = pulseIn(echoPin, HIGH); /* ses dalgasının geri dönmesi için geçen sure ölçülüyor */
  uzaklik = sure /29.1/2; /* ölçülen sure uzaklığa çevriliyor */            
  if(uzaklik > 35) uzaklik = 35;
  if(uzaklik < 0) uzaklik = 0;

  yuzde1 = 100-(uzaklik * 100 / maxMesafe);

  Serial.print("Uzaklik ");  
  Serial.print(uzaklik); /* hesaplanan uzaklık bilgisayara aktarılıyor */
  Serial.print(" CM olarak olculmustur.");
  Serial.print("-----> %");  
  Serial.println(yuzde1);
 
  delay(1000); 

  digitalWrite(trig2Pin, LOW); /* sensör pasif hale getirildi */
  delayMicroseconds(5);
  digitalWrite(trig2Pin, HIGH); /* Sensore ses dalgasının üretmesi için emir verildi */
  delayMicroseconds(10);
  digitalWrite(trig2Pin, LOW);  /* Yeni dalgaların üretilmemesi için trig pini LOW konumuna getirildi */ 
  sure2 = pulseIn(echo2Pin, HIGH); /* ses dalgasının geri dönmesi için geçen sure ölçülüyor */
  uzaklik2 = sure2 /29.1/2; /* ölçülen sure uzaklığa çevriliyor */            
  if(uzaklik2 > 35) uzaklik2 = 35;
  if(uzaklik2 < 0) uzaklik2 = 0;

  yuzde2 = 100-(uzaklik2 * 100 / maxMesafe);

    
  Serial.print("Uzaklik2 ");  
  Serial.print(uzaklik2); /* hesaplanan uzaklık bilgisayara aktarılıyor */
  Serial.print(" CM olarak olculmustur.");
  Serial.print("-----> %");  
  Serial.println(yuzde2);  
  
  delay(1000);

  if(yuzde1>=75 & yuzde2>=75){
    digitalWrite(LED_BUILTIN, HIGH);  
  }else{
    digitalWrite(LED_BUILTIN, LOW);
  }
  
    
    parametre = "";
    obj["No"] = 9;
    obj["Doluluk"] = (yuzde1+yuzde2)/2;
    obj.printTo(parametre);
    Serial.println(parametre);
    Serial.println("Gonderiliyor....");
    gsm_sendhttp();
  
}
