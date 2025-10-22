<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentFieldsToTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_transactions', [
            'payment_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0,
                'after' => 'grand_total'
            ],
            'change_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0,
                'after' => 'payment_amount'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_transactions', ['payment_amount', 'change_amount']);
    }
}
