<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUniqueCodeToMembers extends Migration
{
    public function up()
    {
        if (!$this->forge->getConnection()->fieldExists('unique_code', 'tb_members')) {
            $fields = [
                'unique_code' => [
                    'type' => 'VARCHAR',
                    'constraint' => 64,
                    'null' => false,
                ],
            ];

            $this->forge->addColumn('tb_members', $fields);
        }
    }

    public function down()
    {
        if ($this->forge->getConnection()->fieldExists('unique_code', 'tb_members')) {
            $this->forge->dropColumn('tb_members', 'unique_code');
        }
    }
}
