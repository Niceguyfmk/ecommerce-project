<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriesTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "category_id" => [
                "type"=> "INT",
                "auto_increment" => true,
                "unsigned"=> true
            ],
            "category_name" => [
                "type"=> "VARCHAR",
                "constraint" => "255",
            ],
            "parent_id" => [ //for heirarchical structure NULL for top levels
                "type"=> "INT",
                "default" => NULL,
            ],
            "created_at timestamp default current_timestamp",
        ]);

        $this->forge->addPrimaryKey("category_id");
        $this->forge->createTable("product_categories");
    }

    public function down()
    {
        $this->forge->dropTable("product_categories");
    }
}
