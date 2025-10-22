<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_transaction' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'ppn_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => false,
                'default' => 0,
            ],
            'discount_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => false,
                'default' => 0,
            ],
            'ppn_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0,
            ],
            'discount_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0,
            ],
            'grand_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'payment_method' => [
                'type' => 'ENUM',
                'constraint' => ['cash', 'card', 'transfer'],
                'default' => 'cash',
                'null' => false,
            ],
            'tanggal' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'id_member' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'completed', 'cancelled'],
                'default' => 'pending',
                'null' => false,
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
        $this->forge->addForeignKey('id_member', 'tb_members', 'id_member', 'CASCADE', 'SET NULL');
        $this->forge->createTable('tb_transactions');
    }

    public function down()
    {
        $this->forge->dropTable('tb_transactions');
    }
}
