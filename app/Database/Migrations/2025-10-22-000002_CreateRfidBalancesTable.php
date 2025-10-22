<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRfidBalancesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_balance' => [
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
            'balance' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => '0.00',
            ],
            'last_updated' => [
                'type' => 'DATETIME',
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

        $this->forge->addKey('id_balance', true);
        $this->forge->addKey('id_rfid');

        $this->forge->addForeignKey('id_rfid', 'tb_rfid_cards', 'id_rfid', 'CASCADE', 'CASCADE');

        $this->forge->createTable('tb_rfid_balances');
    }

    public function down()
    {
        $this->forge->dropTable('tb_rfid_balances');
    }
}
