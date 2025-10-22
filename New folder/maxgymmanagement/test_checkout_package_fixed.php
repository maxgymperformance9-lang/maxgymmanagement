<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/admin/kasir/checkout');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'cart' => json_encode([
        ['id_package' => 1, 'nama_produk' => 'Basic Membership', 'harga' => 350000, 'quantity' => 1]
    ]),
    'member_id' => '6', // Use existing member ID 6
    'payment_method' => 'cash',
    'payment_amount' => 350000,
    'ppn_percentage' => 0,
    'discount_percentage' => 0
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
