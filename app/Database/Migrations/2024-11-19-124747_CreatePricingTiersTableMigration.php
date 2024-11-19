<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePricingTiersTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "pricing_tiers_id" => [
                "type" => "INT",
                "auto_increment" => true,
                "unsigned" => true,
            ],
            "product_attribute_id" => [
                "type" => "INT",
                "unsigned" => true,
            ],
            "min_quantity" => [
                "type" => "FLOAT",
            ],
            "max_quantity" => [
                "type" => "FLOAT",
                "null" => true,
            ],
            "tier_price" => [
                "type" => "DECIMAL",
                "constraint" => "10,2",
            ],
        ]);

        $this->forge->addPrimaryKey("pricing_tiers_id");
        $this->forge->createTable("pricing_tiers");
    }

    public function down()
    {
        $this->forge->dropTable("pricing_tiers");
    }
}
