<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClassSchedulesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_schedule' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_class' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'waktu_mulai' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'waktu_selesai' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'instructor' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'lokasi' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'kapasitas_terisi' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['scheduled', 'ongoing', 'completed', 'cancelled'],
                'default' => 'scheduled',
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

        $this->forge->addKey('id_schedule', true);
        $this->forge->addForeignKey('id_class', 'tb_fitness_classes', 'id_class', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_class_schedules');
    }

    public function down()
    {
        $this->forge->dropTable('tb_class_schedules');
    }
}
