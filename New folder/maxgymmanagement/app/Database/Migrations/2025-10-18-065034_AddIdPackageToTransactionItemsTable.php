<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdPackageToTransactionItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_transaction_items', [
            'id_package' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'id_product'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_transaction_items', 'id_package');
    }
}
