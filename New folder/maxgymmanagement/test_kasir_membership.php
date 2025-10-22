<?php
// Test script to simulate kasir membership package checkout
$ch = curl_init();

// First, get the CSRF token by visiting the kasir page
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/admin/kasir');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
$page = curl_exec($ch);

// Extract CSRF token (simple regex - adjust based on your template)
preg_match('/name="' . preg_quote('csrf_test_name', '/') . '"\s+value="([^"]+)"/', $page, $matches);
$csrf_token = $matches[1] ?? '';
$csrf_hash = $matches[1] ?? '';

echo "CSRF Token: $csrf_token\n";

// Now simulate adding a membership package to cart and checkout
// Cart data for membership package (Basic Membership - ID 1)
$cart = [
    [
        'id_package' => 1,
        'nama_produk' => 'Basic Membership',
        'harga' => 500000,
        'quantity' => 1
    ]
];

$postData = [
    'cart' => json_encode($cart),
    'member_id' => '1', // Select member ID 1
    'payment_method' => 'cash',
    'payment_amount' => 500000,
    'ppn_percentage' => 0,
    'discount_percentage' => 0
];

if ($csrf_token) {
    $postData[$csrf_token] = $csrf_hash;
}

curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/admin/kasir/checkout');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
    'X-Requested-With: XMLHttpRequest'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";

// Parse JSON response
$data = json_decode($response, true);
if ($data && isset($data['success'])) {
    if ($data['success']) {
        echo "\n✅ SUCCESS: Membership package checkout worked!\n";
        echo "Transaction ID: " . ($data['transaction_id'] ?? 'N/A') . "\n";

        // Test receipt generation
        if (isset($data['transaction_id'])) {
            $receiptUrl = 'http://localhost:8080/admin/kasir/receipt/' . $data['transaction_id'];
            echo "Receipt URL: $receiptUrl\n";

            // Test receipt access
            $ch2 = curl_init();
            curl_setopt($ch2, CURLOPT_URL, $receiptUrl);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_COOKIEFILE, 'cookies.txt');
            $receipt = curl_exec($ch2);
            curl_close($ch2);

            if (strpos($receipt, 'Basic Membership') !== false && strpos($receipt, '(Paket Membership)') !== false) {
                echo "✅ Receipt generated correctly with membership package label!\n";
            } else {
                echo "❌ Receipt may not be displaying correctly\n";
            }
        }
    } else {
        echo "\n❌ FAILED: " . ($data['message'] ?? 'Unknown error') . "\n";
    }
} else {
    echo "\n❌ INVALID RESPONSE: Could not parse JSON response\n";
}

// Clean up
if (file_exists('cookies.txt')) {
    unlink('cookies.txt');
}
