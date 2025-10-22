<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_di' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'di' => [
                'type'           => 'VARCHAR',
                'constraint'     => 32,
            ],
            'id_wilayah' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'deleted_at TIMESTAMP NULL',
        ]);

        // primary key
        $this->forge->addKey('id_di', primary: TRUE);

        // id_wilayah foreign key
        $this->forge->addForeignKey('id_wilayah', 'tb_wilayah', 'id', 'CASCADE', 'NO ACTION');

        $this->forge->createTable('tb_di', TRUE);
    }

    public function down()
    {
        $this->forge->dropTable('tb_di');
    }
}
