<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailToMembersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_members', [
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'no_hp'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_members', 'email');
    }
}
