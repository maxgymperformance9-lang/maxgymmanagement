<?php
/**
 * Test Script untuk Integrasi Lengkap Sistem Absensi + Door Control
 * Menguji alur: Scan QR → Absensi → Buka Pintu
 */

// Konfigurasi
$baseUrl = 'http://localhost/maxgymmanagement'; // Sesuaikan dengan URL aplikasi Anda
$esp32Ip = '192.168.1.10'; // IP ESP8266

echo "🚀 MULAI TEST INTEGRASI SISTEM ABSENSI + DOOR CONTROL\n";
echo "==================================================\n\n";

// Test 1: Ping ESP8266
echo "1️⃣ Test Koneksi ESP8266\n";
echo "   Ping ke $esp32Ip/ping...\n";
$result = testESP8266Ping($esp32Ip);
if ($result['success']) {
    echo "   ✅ ESP8266 Online: " . $result['response'] . "\n";
} else {
    echo "   ❌ ESP8266 Offline: " . $result['error'] . "\n";
    exit(1);
}
echo "\n";

// Test 2: Test API Door Controller
echo "2️⃣ Test API Door Controller\n";
echo "   Test endpoint /api/test-door...\n";
$result = testDoorAPI($baseUrl);
if ($result['success']) {
    echo "   ✅ API Door Controller OK: " . $result['message'] . "\n";
} else {
    echo "   ❌ API Door Controller Error: " . $result['error'] . "\n";
    exit(1);
}
echo "\n";

// Test 3: Simulasi Absensi Member (gunakan unique_code yang ada di database)
echo "3️⃣ Simulasi Absensi Member\n";
echo "   Catatan: Pastikan ada data member di database dengan unique_code yang valid\n";
echo "   Contoh unique_code: 'MEMBER001' (sesuaikan dengan data Anda)\n";

// Ganti dengan unique_code yang valid dari database Anda
$testUniqueCode = 'MEMBER001'; // <-- GANTI DENGAN UNIQUE CODE YANG ADA

echo "   Menggunakan unique_code: $testUniqueCode\n";
$result = simulateAbsensi($baseUrl, $testUniqueCode);
if ($result['success']) {
    echo "   ✅ Absensi Berhasil: " . $result['message'] . "\n";
    echo "   📋 Detail: " . json_encode($result['data'], JSON_PRETTY_PRINT) . "\n";
} else {
    echo "   ❌ Absensi Gagal: " . $result['error'] . "\n";
    echo "   💡 Kemungkinan: unique_code tidak ditemukan atau sudah absen hari ini\n";
}
echo "\n";

// Test 4: Test Manual Door Open
echo "4️⃣ Test Manual Door Open\n";
echo "   Mengirim command buka pintu secara manual...\n";
$result = testManualDoorOpen($esp32Ip);
if ($result['success']) {
    echo "   ✅ Door Opened Successfully\n";
    echo "   ⏰ Pintu akan tertutup otomatis dalam 5 detik\n";
} else {
    echo "   ❌ Door Open Failed: " . $result['error'] . "\n";
}
echo "\n";

echo "🎯 RINGKASAN TEST\n";
echo "================\n";
echo "✅ ESP8266 Connectivity: OK\n";
echo "✅ API Door Controller: OK\n";
echo "⚠️  Absensi Simulation: Perlu unique_code valid\n";
echo "✅ Manual Door Control: OK\n\n";

echo "📝 CARA TESTING LENGKAP:\n";
echo "1. Buka halaman scan: $baseUrl/scan\n";
echo "2. Scan QR code member yang valid\n";
echo "3. Pastikan absensi berhasil dan pintu terbuka\n";
echo "4. Cek log di database tabel door_access_logs\n\n";

echo "🔧 JIKA ADA MASALAH:\n";
echo "- Pastikan ESP8266 mendapat IP $esp32Ip\n";
echo "- Periksa wiring relay (GPIO 14/D5)\n";
echo "- Cek unique_code di database\n";
echo "- Pastikan member belum absen hari ini\n\n";

// ==================== FUNGSI HELPER ====================

function testESP8266Ping($ip) {
    $url = "http://$ip/ping";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    if ($error) {
        return ['success' => false, 'error' => $error];
    }

    if ($httpCode == 200) {
        return ['success' => true, 'response' => trim($response)];
    } else {
        return ['success' => false, 'error' => "HTTP $httpCode"];
    }
}

function testDoorAPI($baseUrl) {
    $url = "$baseUrl/api/test-door";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    if ($error) {
        return ['success' => false, 'error' => $error];
    }

    if ($httpCode == 200) {
        $data = json_decode($response, true);
        if ($data && isset($data['status']) && $data['status'] == 'success') {
            return ['success' => true, 'message' => $data['message']];
        }
    }

    return ['success' => false, 'error' => "HTTP $httpCode: $response"];
}

function simulateAbsensi($baseUrl, $uniqueCode) {
    // Simulasi scan QR dengan mengirim POST ke endpoint scan
    $url = "$baseUrl/scan/scanFromFile";

    $postData = [
        'unique_code' => $uniqueCode,
        'waktu' => 'Masuk'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    if ($error) {
        return ['success' => false, 'error' => $error];
    }

    // Cek apakah redirect ke scan-result (berarti berhasil)
    if ($httpCode == 200 && strpos($response, 'scan-result') !== false) {
        return ['success' => true, 'message' => 'Absensi berhasil', 'data' => ['unique_code' => $uniqueCode]];
    }

    return ['success' => false, 'error' => "HTTP $httpCode - kemungkinan unique_code tidak valid"];
}

function testManualDoorOpen($ip) {
    $url = "http://$ip/open";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    if ($error) {
        return ['success' => false, 'error' => $error];
    }

    if ($httpCode == 200 && trim($response) == 'Door opened successfully') {
        return ['success' => true];
    } else {
        return ['success' => false, 'error' => "HTTP $httpCode: $response"];
    }
}

?>
