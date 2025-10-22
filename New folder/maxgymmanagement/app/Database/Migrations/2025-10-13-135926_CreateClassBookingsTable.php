<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClassBookingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_booking' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_schedule' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'id_member' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'tanggal_booking' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['booked', 'attended', 'cancelled', 'no_show'],
                'default' => 'booked',
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

        $this->forge->addKey('id_booking', true);
        $this->forge->addForeignKey('id_schedule', 'tb_class_schedules', 'id_schedule', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_member', 'tb_members', 'id_member', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_class_bookings');
    }

    public function down()
    {
        $this->forge->dropTable('tb_class_bookings');
    }
}
