<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_transaction_item' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_transaction' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'id_product' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'nama_produk' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'harga' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'subtotal' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
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

        $this->forge->addKey('id_transaction_item', true);
        $this->forge->addForeignKey('id_transaction', 'tb_transactions', 'id_transaction', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_product', 'tb_products', 'id_product', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_transaction_items');
    }

    public function down()
    {
        $this->forge->dropTable('tb_transaction_items');
    }
}
