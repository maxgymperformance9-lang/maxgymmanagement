<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBenefitsToMembershipPackagesTable extends Migration
{
    public function up()
    {
        // Columns already exist in the create table migration
        // This migration is redundant
    }

    public function down()
    {
        // No columns to drop as they were not added in up()
    }
}
