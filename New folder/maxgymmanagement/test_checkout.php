<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/admin/kasir/checkout');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'cart' => [
        ['id_product' => 1, 'nama_produk' => 'Protein Whey', 'harga' => 150000, 'quantity' => 1]
    ],
    'member_id' => '',
    'payment_method' => 'cash',
    'ppn_percentage' => 0,
    'discount_percentage' => 0
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
