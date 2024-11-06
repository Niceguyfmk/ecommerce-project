<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAttributesTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "attribute_id" => [
                "type"=> "INT",
                "auto_increment" => true,
                "unsigned"=> true
            ],
            "attribute_name" => [
                "type"=> "VARCHAR",
                "constraint" => "50",
            ],
            "created_at timestamp default current_timestamp" 
        ]);

        $this->forge->addPrimaryKey("attribute_id");
        $this->forge->createTable("attributes");
    }

    public function down()
    {
        $this->forge->dropTable("attributes");
    }
}
