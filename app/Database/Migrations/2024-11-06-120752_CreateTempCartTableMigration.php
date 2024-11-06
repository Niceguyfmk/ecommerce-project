<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTempCartTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "session_id" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "primary_key" => true
            ],
            "created_at timestamp default current_timestamp" 
        ]);

        $this->forge->createTable("temp_cart");
    }

    public function down()
    {
        $this->forge->dropTable("temp_cart");
    }
}
