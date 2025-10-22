#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <SPI.h>
#include <MFRC522.h>

// WiFi credentials - Ganti dengan SSID dan password WiFi Anda
const char* ssid = "Agus";
const char* password = "12345678";

// Pin untuk relay pintu (ganti sesuai dengan pin ESP8266 yang digunakan)
const int doorRelayPin = 14; // Pin GPIO D5 untuk relay

// Pin untuk MFRC522 RFID
#define SS_PIN 4  // Pin GPIO D2 untuk SDA (SS)
#define RST_PIN 5 // Pin GPIO D1 untuk RST

// Durasi pintu terbuka dalam milidetik (misalnya 5 detik)
const unsigned long doorOpenDuration = 15000;

// Server API untuk validasi kartu RFID
const char* serverIP = "192.168.1.100"; // Ganti dengan IP server gym management
const int serverPort = 80;

// Web server pada port 80
ESP8266WebServer server(80);

// MFRC522 instance
MFRC522 mfrc522(SS_PIN, RST_PIN);

// Variabel untuk tracking status pintu
bool doorOpen = false;
unsigned long doorOpenTime = 0;

// Variabel untuk debounce RFID
unsigned long lastRFIDRead = 0;
const unsigned long RFID_DEBOUNCE_DELAY = 2000; // 2 detik delay antar scan

void setup() {
  Serial.begin(9600);

  // Setup pin relay sebagai output
  pinMode(doorRelayPin, OUTPUT);
  digitalWrite(doorRelayPin, HIGH); // Relay OFF (pintu terkunci)

  // Inisialisasi SPI dan MFRC522
  SPI.begin();
  mfrc522.PCD_Init();

  Serial.println("RFID Door Control System");
  Serial.println("MFRC522 initialized");

  // Koneksi ke WiFi
  WiFi.begin(ssid, password);
  Serial.print("Connecting to WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi. IP address: ");
  Serial.println(WiFi.localIP());

  // Setup routes
  server.on("/open", HTTP_GET, handleOpenDoor);
  server.on("/ping", HTTP_GET, handlePing);
  server.on("/validate", HTTP_GET, handleValidateCard);
  server.onNotFound(handleNotFound);

  // Start server
  server.begin();
  Serial.println("HTTP server started");
}

void loop() {
  server.handleClient();

  // Cek apakah pintu perlu ditutup
  if (doorOpen && (millis() - doorOpenTime >= doorOpenDuration)) {
    closeDoor();
  }

  // Cek RFID card
  checkRFIDCard();
}

void checkRFIDCard() {
  // Cek apakah ada kartu baru
  if (!mfrc522.PICC_IsNewCardPresent()) {
    return;
  }

  // Pilih kartu
  if (!mfrc522.PICC_ReadCardSerial()) {
    return;
  }

  // Debounce check
  if (millis() - lastRFIDRead < RFID_DEBOUNCE_DELAY) {
    Serial.println("RFID read too soon, ignoring...");
    mfrc522.PICC_HaltA();
    return;
  }

  lastRFIDRead = millis();

  // Baca UID kartu
  String cardUID = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    cardUID += String(mfrc522.uid.uidByte[i], HEX);
  }
  cardUID.toUpperCase();

  Serial.print("Card detected: ");
  Serial.println(cardUID);

  // Validasi kartu dengan server
  if (validateCardWithServer(cardUID)) {
    Serial.println("Card validated successfully, opening door...");
    openDoor();
  } else {
    Serial.println("Card validation failed");
    // Buzzer atau indikator error bisa ditambahkan di sini
  }

  // Halt PICC
  mfrc522.PICC_HaltA();
  // Stop encryption on PCD
  mfrc522.PCD_StopCrypto1();
}

bool validateCardWithServer(String cardUID) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi not connected, cannot validate card");
    return false;
  }

  WiFiClient client;
  if (!client.connect(serverIP, serverPort)) {
    Serial.println("Connection to server failed");
    return false;
  }

  // Buat request ke API validasi
  String url = "/scan/validate?card_uid=" + cardUID;
  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
               "Host: " + serverIP + "\r\n" +
               "Connection: close\r\n\r\n");

  // Tunggu response
  unsigned long timeout = millis();
  while (client.available() == 0) {
    if (millis() - timeout > 5000) {
      Serial.println("Timeout waiting for server response");
      client.stop();
      return false;
    }
  }

  // Baca response
  String response = "";
  while (client.available()) {
    response += client.readStringUntil('\r');
  }

  client.stop();

  Serial.println("Server response: " + response);

  // Parse response - cari "success":true
  if (response.indexOf("\"success\":true") != -1) {
    return true;
  }

  return false;
}

void handleOpenDoor() {
  Serial.println("Door open request received");

  // Buka pintu
  openDoor();

  // Kirim response
  server.send(200, "text/plain", "Door opened successfully");
}

void handlePing() {
  Serial.println("Ping request received");

  // Kirim response dengan status
  String response = "ESP8266 RFID Door Control is online. Door status: " + String(doorOpen ? "open" : "closed");
  server.send(200, "text/plain", response);
}

void handleValidateCard() {
  if (server.hasArg("card_uid")) {
    String cardUID = server.arg("card_uid");
    bool isValid = validateCardWithServer(cardUID);

    String response = "{";
    response += "\"success\":" + String(isValid ? "true" : "false");
    response += ",\"card_uid\":\"" + cardUID + "\"";
    response += "}";

    server.send(200, "application/json", response);
  } else {
    server.send(400, "application/json", "{\"success\":false,\"message\":\"card_uid parameter required\"}");
  }
}

void handleNotFound() {
  server.send(404, "text/plain", "Not found");
}

void openDoor() {
  digitalWrite(doorRelayPin, LOW); // Relay ON (pintu terbuka)
  doorOpen = true;
  doorOpenTime = millis();
  Serial.println("Door opened");
}

void closeDoor() {
  digitalWrite(doorRelayPin, HIGH); // Relay OFF (pintu terkunci)
  doorOpen = false;
  Serial.println("Door closed");
}
