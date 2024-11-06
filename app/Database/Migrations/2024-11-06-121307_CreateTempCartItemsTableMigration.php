<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTempCartItemsTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "temp_cart_item_id" => [
                "type" => "INT",
                "auto_increment" => true,
                "unsigned" => true
            ],
            "session_id" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "null" => false,
            ],
            "product_attribute_id" => [
                "type" => "INT",
                "null" => false,
                "unsigned" => true
            ],
            "quantity" => [
                "type" => "INT",
                "null" => false
            ],
            "price" => [
                "type" => "DECIMAL",
                "constraint" => "10,2",
                "null" => false
            ],
        ]);

        $this->forge->addPrimaryKey("temp_cart_item_id");
        $this->forge->createTable("temp_cart_items");
    }

    public function down()
    {
        $this->forge->dropTable("temp_cart_items");
    }
}
