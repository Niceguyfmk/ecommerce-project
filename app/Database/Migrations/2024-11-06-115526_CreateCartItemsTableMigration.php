<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCartItemsTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "cart_item_id" => [
                "type" => "INT",
                "auto_increment" => true,
                "unsigned" => true
            ],
            "cart_id" => [
                "type" => "INT",
                "null" => false,
                "unsigned" => true
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

        $this->forge->addPrimaryKey("cart_item_id");
        $this->forge->addForeignKey("cart_id", "cart", "cart_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("product_attribute_id", "product_attributes", "product_attribute_id", "CASCADE", "CASCADE");
        $this->forge->createTable("cart_items");
    }

    public function down()
    {
        $this->forge->dropTable("cart_items");
    }
}
