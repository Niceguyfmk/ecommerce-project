<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCouponsTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "coupon_id" => [
                "type" => "INT",
                "auto_increment" => true,
                "unsigned" => true
            ],
            "code" => [
                "type" => "VARCHAR",
                "constraint" => "50",
                "unique" => true,
                "null" => false
            ],
            "discount_type" => [
                "type" => "ENUM",
                "constraint" => ["percentage", "fixed"],
                "null" => false
            ],
            "discount_value" => [
                "type" => "DECIMAL",
                "constraint" => "10,2",
                "null" => false
            ],
            "expiry_date" => [
                "type" => "DATE",
                "null" => true
            ],
            "min_order_amount" => [
                "type" => "DECIMAL",
                "constraint" => "10,2",
                "default" => 0.00
            ],
            "max_usage" => [
                "type" => "INT",
                "null" => true,
                "default" => null
            ],
        ]);

        $this->forge->addPrimaryKey("coupon_id");
        $this->forge->createTable("coupons");
    }

    public function down()
    {
        $this->forge->dropTable("coupons");
    }
}
