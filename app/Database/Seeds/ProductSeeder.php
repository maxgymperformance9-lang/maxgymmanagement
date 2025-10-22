<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_produk' => 'Protein Whey',
                'harga' => 150000,
                'stok' => 50,
                'kategori' => 'Suplemen',
                'deskripsi' => 'Protein whey berkualitas tinggi untuk pemulihan otot',
                'status' => 'active'
            ],
            [
                'nama_produk' => 'Creatine Monohydrate',
                'harga' => 250000,
                'stok' => 30,
                'kategori' => 'Suplemen',
                'deskripsi' => 'Creatine untuk meningkatkan kekuatan dan performa',
                'status' => 'active'
            ],
            [
                'nama_produk' => 'BCAA 2:1:1',
                'harga' => 180000,
                'stok' => 40,
                'kategori' => 'Suplemen',
                'deskripsi' => 'Asam amino esensial untuk pemulihan otot',
                'status' => 'active'
            ],
            [
                'nama_produk' => 'Pre-Workout',
                'harga' => 120000,
                'stok' => 25,
                'kategori' => 'Suplemen',
                'deskripsi' => 'Suplemen pra-latihan untuk energi maksimal',
                'status' => 'active'
            ],
            [
                'nama_produk' => 'Vitamin Gym',
                'harga' => 80000,
                'stok' => 60,
                'kategori' => 'Vitamin',
                'deskripsi' => 'Multivitamin khusus untuk aktifitas fitness',
                'status' => 'active'
            ],
            [
                'nama_produk' => 'Shaker Botol',
                'harga' => 25000,
                'stok' => 100,
                'kategori' => 'Aksesoris',
                'deskripsi' => 'Botol shaker stainless steel 600ml',
                'status' => 'active'
            ],
            [
                'nama_produk' => 'Resistance Band',
                'harga' => 75000,
                'stok' => 35,
                'kategori' => 'Aksesoris',
                'deskripsi' => 'Resistance band set lengkap untuk latihan',
                'status' => 'active'
            ],
            [
                'nama_produk' => 'Foam Roller',
                'harga' => 95000,
                'stok' => 20,
                'kategori' => 'Aksesoris',
                'deskripsi' => 'Foam roller untuk pemanasan dan recovery',
                'status' => 'active'
            ]
        ];

        $this->db->table('tb_products')->insertBatch($data);
    }
}
