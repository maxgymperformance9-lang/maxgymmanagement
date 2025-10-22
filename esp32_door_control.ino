#include <WiFi.h>
#include <WebServer.h>

// WiFi credentials - Ganti dengan SSID dan password WiFi Anda
const char* ssid = "YourWiFiSSID";
const char* password = "YourWiFiPassword";

// Pin untuk relay pintu (ganti sesuai dengan pin ESP32 yang digunakan)
const int doorRelayPin = 13; // Pin GPIO 13 untuk relay

// Durasi pintu terbuka dalam milidetik (misalnya 5 detik)
const unsigned long doorOpenDuration = 5000;

// Web server pada port 80
WebServer server(80);

// Variabel untuk tracking status pintu
bool doorOpen = false;
unsigned long doorOpenTime = 0;

void setup() {
  Serial.begin(115200);

  // Setup pin relay sebagai output
  pinMode(doorRelayPin, OUTPUT);
  digitalWrite(doorRelayPin, HIGH); // Relay OFF (pintu terkunci)

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
  String response = "ESP32 is online. Door status: " + String(doorOpen ? "open" : "closed");
  server.send(200, "text/plain", response);
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
