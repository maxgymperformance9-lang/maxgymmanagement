<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RfidSeeder extends Seeder
{
    public function run()
    {
        // Seed RFID Cards
        $rfidCards = [
            [
                'rfid_uid' => 'ABC123456789',
                'id_member' => null,
                'card_status' => 'active',
                'issued_date' => date('Y-m-d H:i:s'),
                'expiry_date' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'rfid_uid' => 'DEF987654321',
                'id_member' => null,
                'card_status' => 'active',
                'issued_date' => date('Y-m-d H:i:s'),
                'expiry_date' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'rfid_uid' => 'GHI456789123',
                'id_member' => null,
                'card_status' => 'inactive',
                'issued_date' => date('Y-m-d H:i:s'),
                'expiry_date' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('tb_rfid_cards')->insertBatch($rfidCards);

        // Seed RFID Balances
        $rfidBalances = [
            [
                'id_rfid' => 1,
                'balance' => 50000.00,
                'last_updated' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rfid' => 2,
                'balance' => 25000.00,
                'last_updated' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('tb_rfid_balances')->insertBatch($rfidBalances);

        // Seed RFID Transactions
        $rfidTransactions = [
            [
                'id_rfid' => 1,
                'transaction_type' => 'topup',
                'amount' => 50000.00,
                'description' => 'Initial topup',
                'transaction_date' => date('Y-m-d H:i:s'),
                'processed_by' => 1, // Assuming admin user ID
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rfid' => 2,
                'transaction_type' => 'topup',
                'amount' => 25000.00,
                'description' => 'Initial topup',
                'transaction_date' => date('Y-m-d H:i:s'),
                'processed_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('tb_rfid_transactions')->insertBatch($rfidTransactions);
    }
}
