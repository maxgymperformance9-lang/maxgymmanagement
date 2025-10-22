<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_gudang' => 'Gudang Utama',
                'lokasi' => 'Jl. Raya Utama No. 1',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama_gudang' => 'Gudang Cabang A',
                'lokasi' => 'Jl. Cabang A No. 10',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('tb_warehouses')->insertBatch($data);
    }
}
