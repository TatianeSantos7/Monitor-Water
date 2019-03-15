//Instituto Federal de Educação Ciência e Tecnologia do Amazonas (CMDI)
//Trabalho de TCC 2018
//Tatiane Pinto dos Santos
//=========================================================================================================================
//Configuração do Wi-Fi
#include <ESP8266WiFi.h>                                                                           
#include <WiFiClient.h>
const char ssid[] = "TOPS BURGUER";
const char psw[] = "94118648sh";
const char http_site[] = "10.0.0.108";
const int http_port = 80;
WiFiClient client;
IPAddress server(10,0,0,108); //Endereço IP do servidor - http_site
String Mensagem;
//=========================================================================================================================
//Display I2C
#include <Wire.h>                                           // Biblioteca para comunicação I2C
#include <LiquidCrystal_I2C.h>                              // Bibliotca para uso do display I2C
LiquidCrystal_I2C lcd(0x27, 16, 2);                         // FUNÇÃO DO TIPO "LiquidCrystal_I2C"
const int button= D8; 
unsigned long tempo=0;                                      // tempo inicial antes de acionar o botão
unsigned char ligarB = 0;
//=========================================================================================================================
//Sensor de temperatura
#include <OneWire.h>
#include <DallasTemperature.h>                              // Bibliotecas para uso do sensor de temperatura
#define ONE_WIRE_BUS 2                                      // pino D4 do NodMCU ESP-12
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature Temperatura(&oneWire);  
//=========================================================================================================================
//Variáveis para a função de turbidez
double calc_NTU(double volt);
double NTU=0;
//==========================================================================================================================
// Sensor de pH 
#define temp1 10 // Tempo das amostras para a média móvel
float phValue;
unsigned long int avgValue;                                  //Armazena o valor médio de tensão lido pelo sensor
//==========================================================================================================================
// Definição dos pinos do Multiplexador CD4051
#define S_0 D7
#define S_1 D6
#define S_2 D5
#define Analog_INPUT A0
//==========================================================================================================================
// Variáveis para controle do tempo de aquisição dos sensores
unsigned long tempo_aquisitar0 = 0;
unsigned long tempo_aquisitar1;
//==========================================================================================================================
void setup()
{
  
  lcd.init ();                                              // Inicia o LCD informando o tamanho  de 16 colunas e  2 linhas.
  Serial.begin(115200);                                     // Inicia a comunicação serial
  Temperatura.begin();                                      // Inicia a comunicação com o sensor de temperatura
  // Inicialização dos pinos digitais conectados ao multiplexador
  pinMode(S_0, OUTPUT);
  pinMode(S_1, OUTPUT);
  pinMode(S_2, OUTPUT);
  pinMode(button, INPUT);

  WiFi.begin(ssid, psw);
  if(WiFi.status() != WL_CONNECTED) 
  {
    Serial.println("Wifi não encontrado!");
  }
  else{
    Serial.println("Wifi encontrado!");
  }
}

void loop()
{

  button_display();

  tempo_aquisitar1 = millis();
  if(tempo_aquisitar1 > tempo_aquisitar0 + 10000 )
  {
     tempo_aquisitar0 = tempo_aquisitar1;
        pH ();
        Turbidez();
        temperatura ();
        EscreveNoLCD(); 
        internet();   
  }
}
//=======================================================================================================================
//Funções para execução do projeto 
// Função sensor de Turbidez.

void Turbidez()    
{
  //Seleciona a porta Y1 (Tubidez)
  digitalWrite (S_0, HIGH);
  digitalWrite (S_1, LOW);
  digitalWrite (S_2, LOW);
int sensorTurbidez [20];  
float voltage = 0; 
float ultimaleitura=0;
// for para reduzir a variação de tensão do sensor
for(int i=0; i<20; i++)
{
  sensorTurbidez [i]= (int) analogRead(Analog_INPUT);
  voltage += sensorTurbidez[i];
}
voltage=voltage/20;
NTU=-600.53*voltage*3.3/1023 + 1385.2;
  Serial.println("Turbidez: ");
  Serial.println(voltage,0);
  Serial.print("|");
  Serial.println(NTU,3);
  Serial.print(" ");
}

// Função para conversão de tensão para Turbidez em NTU.(Adaptado de WR Kits). 
double calc_NTU(double volt)
{

  double NTU_val;
   NTU_val = -597.79*volt + 1383.5;
  // A calibração só garante valores até 1000 NTU, devido a isso, qualquer valor acima considera-se 1000NTU.
  if (NTU_val > 1000) NTU_val = 1000; 
  return NTU_val; 
} 
//======================================================================================================================
  // Função sensor de Temperatura

  void temperatura ()
  {
  Temperatura.requestTemperatures();                     // Envia o comando para obter temperatura            
  Serial.println("Temperatura: ");
  Serial.println(Temperatura.getTempCByIndex(0));      // (0)Define o endereço do  sensor    
  }
//======================================================================================================================
// Função sensor de pH (Adaptado do manual DFROBOT)

  void pH()
  {
// Seleciona a porta Y0 (pH)
  digitalWrite (S_0, LOW);
  digitalWrite (S_1, LOW);
  digitalWrite (S_2, LOW);

static unsigned temp= millis ();

int buf[10];                                                  //vetor para armazenar valores analógicos do sensor
  if (millis ()- temp > temp1)
  {  
       temp = millis();
    
      for(int i=0;i<10;i++)                                   //Obtem um valor de 10 amostras do sensor para suavizar a variação de leituras
      {
        buf[i]=analogRead(Analog_INPUT);
    
     }
  }
  for(int i=0;i<9;i++)                                        //Faz a comparação entre o menor e o maior valor dos 10 valores lidos.
  {
    for(int j=i+1;j<10;j++)
    {
      if(buf[i]>buf[j])
      {
        int t=buf[i];
        buf[i]=buf[j];
        buf[j]=t;  
      }
    }
  }
  avgValue=0;
  for(int i=2;i<8;i++) 
    avgValue+=buf[i];
  phValue=(float)avgValue*(3.3/1023)/6;                //converte o valor analógico para millivolt                          
  phValue= 12.97*phValue-8.3111;                       // relação entre o valor de tensão e pH
  
  Serial.print("pH:"); 
  Serial.print(phValue);
  Serial.println(" ");
  
  } 
//====================================================================================================================
//Função para econômia de energia do Display
  void button_display()
{
//debouncer.update();                                    // Executa o algorítimo de tratamento;
//int arm_modo= debouncer.read();                        // recebe o valor tratado do botão;
 int arm_modo = digitalRead(button);
        if (arm_modo== HIGH)                             // Botão Pressionado;
        { 
            tempo= millis ();
            lcd.display();
            lcd.setBacklight(HIGH);
            ligarB = 1;
            EscreveNoLCD();
       }
 
       if (millis()> tempo + 15000)                        //verifica se já passou 15 segundos 
       { 
           lcd.noDisplay();                                // Apaga o que está escrito no display 
           lcd.setBacklight(LOW);                          // desliga o Backlight do LCD
           ligarB = 0;
       }  
}
//===========================================================================================================================
//Função para mostrar os valores dos sensores no display

void EscreveNoLCD()
{
          if(ligarB==1)
          {
           
          //escreve o valor de pH no display  
         
          lcd.setCursor(1,0);
          lcd.print("PH:");
          lcd.print (phValue,1);
          lcd.print ("            ");
          // Escreve o valor da temperatura no Display
   
          lcd.setCursor (1,2);
          lcd.print("Temperatura:");
          lcd.print(Temperatura.getTempCByIndex(0));
          lcd.write(B11011111); //Simbolo de graus celsius
          lcd.print("C");
    
          // Escreve o valor da turbidez no Display
         
          lcd.setCursor (1,4);
          lcd.print("Turbidez:");
          lcd.print(NTU,0);
          lcd.print(" ");
              
        }
}
//===================================================================================================================================
//Verifica se Nodmcu tem conexão com a internet e envia dados ao site
void internet()
{

  if (WiFi.status() == WL_CONNECTED)
  {
        Serial.println("Conectado ao wifi!");
        //Manda os dados ao BD via GET
        if ( !client.connect(server, 80) ) {
          Serial.println("Falha na conexao com o site ");
          return ;
        } 
        client.println("GET /Sensores.php?temp="+String(Temperatura.getTempCByIndex(0))+"&ph="+String(phValue)+"&Turb="+String(NTU)+" HTTP/1.1\r\nHost:"+http_site+"\r\n\r\n");
           alerta();    //Caso necessário envia mensagem de alerta
           while(client.available()){
            String line = client.readStringUntil('\r');
            Serial.print(line);
           }
         client.println ();
  }
  else{
    Serial.println("Não conectado ao wifi, tentando novamente");
    WiFi.begin(ssid, psw);
    if(WiFi.status() != WL_CONNECTED) 
    {
      Serial.println("Wifi não encontrado!");
    }
    else{
      Serial.println("Wifi encontrado!");
    }
  }
}

//======================================================================================================================================
//Manda mensagem de alerta para o WhatsApp

void alerta()
{
        if(phValue > 9.5)
        {
          Mensagem = " Valor%20de%20pH%20acima%20do%20padrão"+String(phValue);
          client.println("GET /mandar_whatsapp.php?mensagem="+String(Mensagem)+" HTTP/1.1\r\nHost:"+http_site+"\r\n\r\n");
        }
        else if(phValue < 6)
        {
          Mensagem =" Valor%20de%20pH%20abaixo%20do%20padrão.pH="+String(phValue);
          client.println("GET /mandar_whatsapp.php?mensagem="+String(Mensagem)+" HTTP/1.1\r\nHost:"+http_site+"\r\n\r\n");
        }
        if(NTU > 80)
        {
          Mensagem = "valor%20de%20turbidez%20acima%20do%20padrão.%20Turbidez="+String(NTU);
          client.println("GET /mandar_whatsapp.php?mensagem="+Mensagem+" HTTP/1.1\r\nHost:"+http_site+"\r\n\r\n");
        }
        if(Temperatura.getTempCByIndex(0)> 40)
        {
          Mensagem ="Temperatura%20está%20muito%20alta.%20Temperatura="+String(Temperatura.getTempCByIndex(0));
          client.println("GET /mandar_whatsapp.php?mensagem="+String(Mensagem)+" HTTP/1.1\r\nHost:"+http_site+"\r\n\r\n");
        }       
}


  
 
