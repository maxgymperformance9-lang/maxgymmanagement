<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMembershipPackagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_package' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_package' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'harga' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'durasi_hari' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'pt_sessions' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'benefits' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'unlimited_classes' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => false,
                'default' => 0,
            ],
            'locker_access' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => false,
                'default' => 0,
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

        $this->forge->addKey('id_package', true);
        $this->forge->createTable('tb_membership_packages');
    }

    public function down()
    {
        $this->forge->dropTable('tb_membership_packages');
    }
}
