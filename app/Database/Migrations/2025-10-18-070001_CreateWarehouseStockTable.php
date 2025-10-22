<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWarehouseStockTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_stock' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_warehouse' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'id_product' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'min_stock' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'max_stock' => [
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

        $this->forge->addKey('id_stock', true);
        $this->forge->addForeignKey('id_warehouse', 'tb_warehouses', 'id_warehouse', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_product', 'tb_products', 'id_product', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_warehouse_stock');
    }

    public function down()
    {
        $this->forge->dropTable('tb_warehouse_stock');
    }
}
