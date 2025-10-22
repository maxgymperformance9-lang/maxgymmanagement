<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockMovementsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_movement' => [
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
            'movement_type' => [
                'type' => 'ENUM',
                'constraint' => ['in', 'out', 'transfer', 'adjustment'],
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'from_warehouse' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'to_warehouse' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'reference' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_movement', true);
        $this->forge->addForeignKey('id_warehouse', 'tb_warehouses', 'id_warehouse', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_product', 'tb_products', 'id_product', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('from_warehouse', 'tb_warehouses', 'id_warehouse', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('to_warehouse', 'tb_warehouses', 'id_warehouse', 'SET NULL', 'SET NULL');
        $this->forge->createTable('tb_stock_movements');
    }

    public function down()
    {
        $this->forge->dropTable('tb_stock_movements');
    }
}
