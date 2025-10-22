<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWarehousesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_warehouse' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_gudang' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'lokasi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active',
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

        $this->forge->addKey('id_warehouse', true);
        $this->forge->createTable('tb_warehouses');
    }

    public function down()
    {
        $this->forge->dropTable('tb_warehouses');
    }
}
