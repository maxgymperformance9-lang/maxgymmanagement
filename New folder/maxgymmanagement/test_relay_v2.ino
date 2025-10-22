#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>

// WiFi credentials - Ganti dengan SSID dan password WiFi Anda
const char* ssid = "Agus";
const char* password = "12345678";

// Pin untuk relay pintu (ganti sesuai dengan pin ESP8266 yang digunakan)
const int doorRelayPin = 15; // Pin GPIO 15 (D8) untuk relay

// Web server pada port 80
ESP8266WebServer server(80);

void setup() {
  Serial.begin(9600);

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
  server.on("/close", HTTP_GET, handleCloseDoor);
  server.on("/test", HTTP_GET, handleTestRelay);
  server.on("/ping", HTTP_GET, handlePing);
  server.onNotFound(handleNotFound);

  // Start server
  server.begin();
  Serial.println("HTTP server started");
}

void loop() {
  server.handleClient();
}

void handleOpenDoor() {
  Serial.println("Door open request received");

  // Buka pintu dengan HIGH (untuk relay yang active HIGH)
  digitalWrite(doorRelayPin, HIGH); // Relay ON (pintu terbuka)
  Serial.println("Door opened - Relay HIGH");

  // Kirim response
  server.send(200, "text/plain", "Door opened successfully - Relay set to HIGH");
}

void handleCloseDoor() {
  Serial.println("Door close request received");

  // Tutup pintu
  digitalWrite(doorRelayPin, LOW); // Relay OFF (pintu terkunci)
  Serial.println("Door closed - Relay LOW");

  // Kirim response
  server.send(200, "text/plain", "Door closed successfully - Relay set to LOW");
}

void handleTestRelay() {
  Serial.println("Testing relay with HIGH/LOW...");

  // Test dengan HIGH (beberapa relay butuh HIGH untuk ON)
  digitalWrite(doorRelayPin, HIGH);
  Serial.println("Relay ON (HIGH) - 2 seconds");
  delay(2000);

  // Test OFF
  digitalWrite(doorRelayPin, LOW);
  Serial.println("Relay OFF (LOW) - 2 seconds");
  delay(2000);

  // Test ON lagi
  digitalWrite(doorRelayPin, HIGH);
  Serial.println("Relay ON (HIGH) - 2 seconds");
  delay(2000);

  // Kembali OFF
  digitalWrite(doorRelayPin, LOW);
  Serial.println("Relay OFF (LOW) - back to default");

  server.send(200, "text/plain", "Relay test completed with HIGH/LOW - check if relay clicked 2 times");
}

void handlePing() {
  Serial.println("Ping request received");

  // Kirim response dengan status
  String response = "ESP8266 is online. IP: " + WiFi.localIP().toString();
  server.send(200, "text/plain", response);
}

void handleNotFound() {
  server.send(404, "text/plain", "Not found");
}
