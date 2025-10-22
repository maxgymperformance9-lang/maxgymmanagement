#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <SPI.h>
#include <MFRC522.h>

// WiFi credentials - Ganti dengan SSID dan password WiFi Anda
const char* ssid = "Agus";
const char* password = "12345678";

// Pin untuk MFRC522 RFID
#define SS_PIN 4  // Pin GPIO D2 untuk SDA (SS)
#define RST_PIN 5 // Pin GPIO D1 untuk RST

// Server API untuk validasi kartu RFID
const char* serverIP = "192.168.1.100"; // Ganti dengan IP server gym management
const int serverPort = 80;

// Web server pada port 80
ESP8266WebServer server(80);

// MFRC522 instance
MFRC522 mfrc522(SS_PIN, RST_PIN);

// Variabel untuk debounce RFID
unsigned long lastRFIDRead = 0;
const unsigned long RFID_DEBOUNCE_DELAY = 2000; // 2 detik delay antar scan

void setup() {
  Serial.begin(9600);

  // Inisialisasi SPI dan MFRC522
  SPI.begin();
  mfrc522.PCD_Init();

  Serial.println("RFID Payment Terminal System");
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
  server.on("/ping", HTTP_GET, handlePing);
  server.on("/validate", HTTP_GET, handleValidateCard);
  server.on("/process-payment", HTTP_POST, handleProcessPayment);
  server.onNotFound(handleNotFound);

  // Start server
  server.begin();
  Serial.println("HTTP server started");
}

void loop() {
  server.handleClient();

  // Cek RFID card untuk payment
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
    Serial.println("Card validated successfully - ready for payment");
  } else {
    Serial.println("Card validation failed");
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

void handlePing() {
  Serial.println("Ping request received");

  // Kirim response dengan status
  String response = "ESP8266 RFID Payment Terminal is online.";
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

void handleProcessPayment() {
  if (server.hasArg("plain")) {
    String jsonData = server.arg("plain");

    // Parse JSON data (simple parsing)
    String cardUID = "";
    String amount = "";

    // Extract card_uid
    int uidStart = jsonData.indexOf("\"card_uid\":\"") + 12;
    int uidEnd = jsonData.indexOf("\"", uidStart);
    if (uidStart > 11 && uidEnd > uidStart) {
      cardUID = jsonData.substring(uidStart, uidEnd);
    }

    // Extract amount
    int amountStart = jsonData.indexOf("\"amount\":") + 9;
    int amountEnd = jsonData.indexOf(",", amountStart);
    if (amountStart > 8 && amountEnd > amountStart) {
      amount = jsonData.substring(amountStart, amountEnd);
    }

    if (cardUID != "" && amount != "") {
      // Process payment via server
      bool paymentSuccess = processPaymentWithServer(cardUID, amount.toFloat());

      String response = "{";
      response += "\"success\":" + String(paymentSuccess ? "true" : "false");
      response += ",\"card_uid\":\"" + cardUID + "\"";
      response += ",\"amount\":" + amount;
      response += "}";

      server.send(200, "application/json", response);
    } else {
      server.send(400, "application/json", "{\"success\":false,\"message\":\"Invalid JSON data\"}");
    }
  } else {
    server.send(400, "application/json", "{\"success\":false,\"message\":\"No data provided\"}");
  }
}

bool processPaymentWithServer(String cardUID, float amount) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi not connected, cannot process payment");
    return false;
  }

  WiFiClient client;
  if (!client.connect(serverIP, serverPort)) {
    Serial.println("Connection to server failed");
    return false;
  }

  // Buat request ke API pembayaran
  String postData = "card_uid=" + cardUID + "&amount=" + String(amount);
  String url = "/api/rfid/process-payment";

  client.print(String("POST ") + url + " HTTP/1.1\r\n" +
               "Host: " + serverIP + "\r\n" +
               "Content-Type: application/x-www-form-urlencoded\r\n" +
               "Content-Length: " + postData.length() + "\r\n" +
               "Connection: close\r\n\r\n" +
               postData);

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

  Serial.println("Payment server response: " + response);

  // Parse response - cari "success":true
  if (response.indexOf("\"success\":true") != -1) {
    return true;
  }

  return false;
}

void handleNotFound() {
  server.send(404, "text/plain", "Not found");
}
