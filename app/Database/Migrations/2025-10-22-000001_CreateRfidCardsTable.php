<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRfidCardsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_rfid' => [
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
            'rfid_uid' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'card_status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'blocked'],
                'default' => 'active',
            ],
            'issued_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'expiry_date' => [
                'type' => 'DATETIME',
                'null' => true,
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

        $this->forge->addKey('id_rfid', true);
        $this->forge->addKey('id_member');
        $this->forge->addUniqueKey('rfid_uid');

        $this->forge->addForeignKey('id_member', 'tb_members', 'id_member', 'SET NULL', 'SET NULL');

        $this->forge->createTable('tb_rfid_cards');
    }

    public function down()
    {
        $this->forge->dropTable('tb_rfid_cards');
    }
}
