<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExpensesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_expense' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => false,
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'expense_date' => [
                'type' => 'DATE',
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

        $this->forge->addKey('id_expense', true);
        $this->forge->createTable('tb_expenses');
    }

    public function down()
    {
        $this->forge->dropTable('tb_expenses');
    }
}
