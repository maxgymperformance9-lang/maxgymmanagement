<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFotoToMembersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_members', [
            'foto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'keterangan'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_members', 'foto');
    }
}
