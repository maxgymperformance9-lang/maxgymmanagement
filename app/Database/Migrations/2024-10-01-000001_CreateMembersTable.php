<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMembersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_member' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_member' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'jenis_kelamin' => [
                'type' => 'ENUM',
                'constraint' => ['Laki-laki','Perempuan'],
                'null' => false,
            ],
            'no_member' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'type_member' => [
                'type' => 'ENUM',
                'constraint' => ['umum', 'pelajar', 'mahasiswa', 'personal_trainer', 'member_pt'],
                'null' => false,
            ],
            'no_hp' => [
                'type' => 'VARCHAR',
                'constraint' => 32,
                'null' => false,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tanggal_join' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'tanggal_expired' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'unique_code' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
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

        $this->forge->addKey('id_member', true);
        $this->forge->createTable('tb_members');
    }

    public function down()
    {
        $this->forge->dropTable('tb_members');
    }
}
