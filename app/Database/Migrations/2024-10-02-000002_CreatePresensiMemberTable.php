<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePresensiMemberTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_presensi_member' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_member' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'jam_masuk' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'jam_keluar' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'id_kehadiran' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'default' => 1,
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

        $this->forge->addKey('id_presensi_member', true);
        $this->forge->addForeignKey('id_member', 'tb_members', 'id_member', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_presensi_member');
    }

    public function down()
    {
        $this->forge->dropTable('tb_presensi_member');
    }
}
