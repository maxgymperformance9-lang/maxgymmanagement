<?php
// Debug the checkout process
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/admin/kasir/checkout');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'cart' => json_encode([
        ['id_package' => 1, 'nama_produk' => 'Basic Membership', 'harga' => 350000, 'quantity' => 1, 'type' => 'package']
    ]),
    'member_id' => '6',
    'payment_method' => 'cash',
    'payment_amount' => 350000,
    'ppn_percentage' => 0,
    'discount_percentage' => 0
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";

// Check member after checkout
$db = mysqli_connect('localhost', 'root', '', 'db_absensi');
$result = mysqli_query($db, 'SELECT id_member, nama_member, id_package, tanggal_bergabung, tanggal_kadaluarsa, status_membership, sisa_pt_sessions FROM tb_members WHERE id_member = 6');
$row = mysqli_fetch_assoc($result);
echo "\nMember after checkout:\n";
print_r($row);
