<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MembershipPackageSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_package' => 'Basic Membership',
                'harga' => 350000.00,
                'durasi_hari' => 30,
                'pt_sessions' => 0,
                'deskripsi' => 'Basic gym membership with access to all facilities',
                'benefits' => '["Access to gym equipment", "Locker access", "Free WiFi"]',
                'unlimited_classes' => 0,
                'locker_access' => 1,
                'status' => 'aktif',
            ],
            [
                'nama_package' => 'Premium Membership',
                'harga' => 750000.00,
                'durasi_hari' => 30,
                'pt_sessions' => 4,
                'deskripsi' => 'Premium membership with personal training sessions',
                'benefits' => '["Access to gym equipment", "4 PT sessions", "Locker access", "Unlimited group classes", "Free WiFi", "Nutrition consultation"]',
                'unlimited_classes' => 1,
                'locker_access' => 1,
                'status' => 'aktif',
            ],
            [
                'nama_package' => 'VIP Membership',
                'harga' => 1050000.00,
                'durasi_hari' => 30,
                'pt_sessions' => 10,
                'deskripsi' => 'VIP membership with unlimited personal training',
                'benefits' => '["Access to gym equipment", "Unlimited PT sessions", "Locker access", "Unlimited group classes", "Free WiFi", "Nutrition consultation", "Priority booking", "Towel service"]',
                'unlimited_classes' => 1,
                'locker_access' => 1,
                'status' => 'aktif',
            ],
        ];

        $this->db->table('tb_membership_packages')->insertBatch($data);
    }
}
