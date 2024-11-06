<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductAttributesTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "product_attribute_id" => [
                "type"=> "INT",
                "auto_increment" => true,
                "unsigned"=> true
            ],
            "product_id" => [
                "type"=> "INT",
                "unsigned"=> true
            ],
            "attribute_id" => [
                "type"=> "INT",
                "unsigned"=> true
            ],
            "attribute_value" => [
                "type"=> "VARCHAR",
                "constraint" => "50",
            ],
            "attribute_price" => [
                "type"=> "decimal",
                "constraint" => "10,2",
            ],
            "quantity" => [
                "type" => "INT",
                "default" => "0",
            ]
        ]);

        $this->forge->addPrimaryKey("product_attribute_id");
        $this->forge->addForeignKey("product_id", "prodducts", "product_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("attribute_id", "attributes", "attribute_id", "CASCADE", "CASCADE");
        $this->forge->createTable("product_attributes");
    }

    public function down()
    {
        $this->forge->dropTable("product_attributes");
    }
}
