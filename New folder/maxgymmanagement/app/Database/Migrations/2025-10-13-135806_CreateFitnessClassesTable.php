<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFitnessClassesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_class' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_class' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'durasi' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'comment' => 'Durasi dalam menit',
            ],
            'kapasitas' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'harga' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0.00,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['aktif', 'nonaktif'],
                'default' => 'aktif',
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

        $this->forge->addKey('id_class', true);
        $this->forge->createTable('tb_fitness_classes');
    }

    public function down()
    {
        $this->forge->dropTable('tb_fitness_classes');
    }
}
