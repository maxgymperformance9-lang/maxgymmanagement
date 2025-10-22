<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFotoToPegawaiTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_pegawai', [
            'foto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'no_hp'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_pegawai', 'foto');
    }
}
