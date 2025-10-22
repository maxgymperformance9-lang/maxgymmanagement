<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDoorAccessLogTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_door_access' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_member' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'id_pegawai' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'id_penjaga' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'jam' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'tipe_user' => [
                'type' => 'ENUM',
                'constraint' => ['member', 'pegawai', 'penjaga'],
                'null' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['success', 'failed'],
                'default' => 'success',
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

        $this->forge->addKey('id_door_access', true);
        $this->forge->addKey('id_member');
        $this->forge->addKey('id_pegawai');
        $this->forge->addKey('id_penjaga');
        $this->forge->createTable('tb_door_access_log');
    }

    public function down()
    {
        $this->forge->dropTable('tb_door_access_log');
    }
}
