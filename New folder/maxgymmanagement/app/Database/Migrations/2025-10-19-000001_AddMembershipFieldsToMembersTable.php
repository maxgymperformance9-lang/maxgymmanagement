<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMembershipFieldsToMembersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_members', [
            'id_package' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'unique_code'
            ],
            'tanggal_bergabung' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'id_package'
            ],
            'tanggal_kadaluarsa' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'tanggal_bergabung'
            ],
            'status_membership' => [
                'type' => 'ENUM',
                'constraint' => ['aktif', 'nonaktif', 'expired'],
                'default' => 'nonaktif',
                'null' => false,
                'after' => 'tanggal_kadaluarsa'
            ],
            'sisa_pt_sessions' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => false,
                'after' => 'status_membership'
            ],
            'locker_number' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
                'after' => 'sisa_pt_sessions'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_members', [
            'id_package',
            'tanggal_bergabung',
            'tanggal_kadaluarsa',
            'status_membership',
            'sisa_pt_sessions',
            'locker_number'
        ]);
    }
}
