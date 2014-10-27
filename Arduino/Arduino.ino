#include <Regexp.h>
#include <Wire.h>
//#include <Adafruit_GFX.h>
//#include <Adafruit_SSD1306.h>
#include <IRremote.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include "SmartHomeAIR.h"

// Arduino數位腳位2接到1-Wire裝置
#define ONE_WIRE_BUS 2

#define NUMFLAKES 10
#define XPOS 0
#define YPOS 1
#define DELTAY 2

uint8_t cnt;

int PassVal = 0;
char Cmd_Buf[8];
char *Cmd;
char Buf[128];
MatchState ms;

unsigned int rawCodes_ac_open[110] = { 1000,3114,914,359,908,350,869,381,812,451,800,454,798,456,780,1568,741,1624,719,543,708,548,697,558,685,575,667,589,654,600,635,619,629,630,625,631,627,627,627,626,628,630,627,628,622,632,622,632,626,632,624,632,628,625,627,627,630,628,629,626,627,627,629,625,627,630,632,623,627,626,632,621,629,629,630,625,627,1717,626, 627,630,1719,633,624,629,625,629, 625,631,1717,634,1713,628,626,627,626,628,1720,633,1713,628,1717,630, 624,629,1721,659,3394,3000,3000};
unsigned int rawCodes_ac_close[110]= { 1000,3332,726,536,665,627,608,602,610,671,664,567,635,617,734, 583,598, 615,658,562,666,640,616,608,638,595,628,652,643,585,632,665,629,603,591,665,594,662,639,605,585,644,664,593,596,833,460,633,601,659,609,639,624,680,577,622,628,601,641,639,722,527,607,594,677,655,571,659,580,670,655,595,658,573,712,586,585,1730,668,1724,591, 605,624,716,541,613,657,1698,620, 621,651,1742,596,620,617,652,622, 626,576,1759,648,1725,586,1782,580,1706,618,3443, 766,3000};
IRsend  irsend;

OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

void setup()   {                
  Serial1.begin(115200);
  Serial.begin(9600);
  delay(100);

  sensors.begin();

  Serial.println(Version);
}

void loop() {
  int ch = 0;
  int i;

  cnt = 0;
  memset(Buf, 0, sizeof(Buf));
  while( ch != 10 )       // if data is available to read
  {
    while( Serial1.available() )       // if data is available to read
    {
      ch = Serial1.read();
      //Serial.print(ch);
      Buf[cnt] = char(ch);
      //Serialprint(char(ch));
      //delay(2);
      if (ch == 10)
      {
        //Serial.println("-q-");
        break;
      }
      cnt++;
      if (cnt >= sizeof(Buf))
        break;
    }
    if (cnt >= sizeof(Buf))
      break;
  }

  Serial.print(Buf);
  Serial.println("---");
  
  ms.Target (Buf);
  if (ms.Match (AccessKey))
  {
    //Serial.print ("Found match at: ");
    //Serial.println (ms.MatchStart);        // 16 in this case     
    //Serial.print ("Match length: ");
    //Serial.println (ms.MatchLength);       // 3 in this case
    Serial.print("head Cmd:");
    ms.Target (Buf);
    ms.Match ("cmd=(%d)");
    Serial.println(ms.GetCapture (Cmd_Buf, 0));
    Cmd = ms.GetCapture (Cmd_Buf, 0);
    PassVal = 1;
  }
 
  if (ms.Match ("\r\n") && ms.MatchStart >= 30)// && (ms.MatchStart == 30 || ms.MatchStart == 51)
  {
    Serial.println(F("output"));
    //Serial.print ("2Found match at: ");
    Serial.println (ms.MatchStart);        // 16 in this case     
    //Serial.print ("2Match length: ");
    Serial.println (ms.MatchLength);       // 3 in this case
    //display.clearDisplay();
    //display.setTextSize(2);
    //display.setTextColor(WHITE);
    //display.setCursor(0,0);
    if (PassVal == 1)
    {
      Serial.println(F("pass"));
      Serial1.println(F("HTTP/1.1 200 OK"));
      Serial1.println(F("Content-Type: text/html"));
      Serial1.println(F("Access-Control-Allow-Origin: *"));
      Serial1.println(F("Connection: close"));
      //Serial1.println(F("Connection: keep-alive"));
      //Serial1.println("Refresh: 10;url=index.htm?cmd=" + (Cmd.toInt() + 1));  // refresh the page automatically every 5 sec
      Serial1.println();
      //Serial1.println(F("<!DOCTYPE HTML>"));
      if (Cmd[0] == '1')
      {
        Serial1.println(F("Opened"));
        Serial.println(F("Opened"));
        //display.println(F("Opened"));
        irsend.sendRaw(rawCodes_ac_open,110, 38);
      }else if (Cmd[0] == '0'){
        Serial1.println(F("Closed"));
        Serial.println(F("Closed"));
        //display.println(F("Closed"));
        irsend.sendRaw(rawCodes_ac_close,110, 38);
      }else if (Cmd[0] == '2'){
        sensors.requestTemperatures();
        Serial1.println(sensors.getTempCByIndex(0));
        Serial.println(sensors.getTempCByIndex(0));
        //display.println(F("Closed"));
      }
      delay(2000);
      Serial1.println(" ");
      delay(2000);
      //display.display();
    }else{
      Serial1.println(F("HTTP/1.1 200 OK"));
      Serial1.println(F("Content-Type: text/html"));
      Serial1.println(F("Connection: close"));
      Serial1.println();
      Serial1.println(F("<!DOCTYPE HTML>"));
      delay(2000);
      Serial1.println(" ");
      delay(2000);
    }
    //Cmd = '';
    PassVal = 0;
  }

  //display.println(cnt);

}

