<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRfidTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_transaction' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_rfid' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'transaction_type' => [
                'type' => 'ENUM',
                'constraint' => ['topup', 'payment', 'refund'],
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'transaction_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'processed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_transaction', true);
        $this->forge->addKey('id_rfid');
        $this->forge->addKey('transaction_type');

        $this->forge->addForeignKey('id_rfid', 'tb_rfid_cards', 'id_rfid', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('processed_by', 'tb_pegawai', 'id_pegawai', 'SET NULL', 'SET NULL');

        $this->forge->createTable('tb_rfid_transactions');
    }

    public function down()
    {
        $this->forge->dropTable('tb_rfid_transactions');
    }
}
