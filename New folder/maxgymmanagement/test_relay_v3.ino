#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>

// WiFi credentials
const char* ssid = "Agus";
const char* password = "12345678";

// Pin untuk relay pintu - GANTI KE GPIO 14 (D5)
const int doorRelayPin = 14; // GPIO 14 (D5) - lebih stabil

// Web server pada port 80
ESP8266WebServer server(80);

void setup() {
  Serial.begin(9600);
  Serial.println("ESP8266 Relay Test v3 - GPIO 14 (D5)");

  // Setup pin relay sebagai output
  pinMode(doorRelayPin, OUTPUT);
  digitalWrite(doorRelayPin, LOW); // Start dengan LOW (relay OFF)

  Serial.print("Setting up GPIO ");
  Serial.print(doorRelayPin);
  Serial.println(" as OUTPUT");

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
  server.on("/status", HTTP_GET, handleStatus);
  server.on("/ping", HTTP_GET, handlePing);
  server.onNotFound(handleNotFound);

  // Start server
  server.begin();
  Serial.println("HTTP server started");

  Serial.println("Setup complete. GPIO 14 ready for relay control.");
}

void loop() {
  server.handleClient();
}

void handleOpenDoor() {
  Serial.println("=== DOOR OPEN REQUEST ===");

  digitalWrite(doorRelayPin, HIGH); // Relay ON
  Serial.println("GPIO 14 set to HIGH - Relay should be ON");
  Serial.println("Check if green LED turns ON and relay clicks");

  server.send(200, "text/plain", "Door opened - GPIO 14 HIGH");
}

void handleCloseDoor() {
  Serial.println("=== DOOR CLOSE REQUEST ===");

  digitalWrite(doorRelayPin, LOW); // Relay OFF
  Serial.println("GPIO 14 set to LOW - Relay should be OFF");
  Serial.println("Check if green LED turns OFF");

  server.send(200, "text/plain", "Door closed - GPIO 14 LOW");
}

void handleTestRelay() {
  Serial.println("=== RELAY TEST START ===");

  // Test 1: ON
  Serial.println("Test 1: Setting GPIO 14 to HIGH");
  digitalWrite(doorRelayPin, HIGH);
  Serial.println("Green LED should turn ON, relay should click");
  delay(3000);

  // Test 2: OFF
  Serial.println("Test 2: Setting GPIO 14 to LOW");
  digitalWrite(doorRelayPin, LOW);
  Serial.println("Green LED should turn OFF");
  delay(3000);

  // Test 3: ON again
  Serial.println("Test 3: Setting GPIO 14 to HIGH again");
  digitalWrite(doorRelayPin, HIGH);
  Serial.println("Green LED should turn ON again, relay should click");
  delay(3000);

  // Back to OFF
  Serial.println("Back to OFF: Setting GPIO 14 to LOW");
  digitalWrite(doorRelayPin, LOW);
  Serial.println("Test completed");

  server.send(200, "text/plain", "Relay test completed - Check serial for details");
}

void handleStatus() {
  int pinState = digitalRead(doorRelayPin);
  String status = "GPIO 14 state: " + String(pinState) + " (" + (pinState == HIGH ? "HIGH" : "LOW") + ")";
  Serial.println("Status check: " + status);
  server.send(200, "text/plain", status);
}

void handlePing() {
  Serial.println("Ping received");
  String response = "ESP8266 online. IP: " + WiFi.localIP().toString() + " GPIO 14 state: " + String(digitalRead(doorRelayPin));
  server.send(200, "text/plain", response);
}

void handleNotFound() {
  server.send(404, "text/plain", "Not found");
}
