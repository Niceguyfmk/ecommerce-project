<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItemsTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "order_item_id" => [
                "type" => "INT",
                "auto_increment" => true,
                "unsigned" => true
            ],
            "order_id" => [
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

        $this->forge->addPrimaryKey("order_item_id");
        $this->forge->addForeignKey("order_id", "orders", "order_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("product_attribute_id", "product_attributes", "product_attribute_id", "CASCADE", "CASCADE");
        $this->forge->createTable("order_items");
    }

    public function down()
    {
        $this->forge->dropTable('order_items');
    }
}
