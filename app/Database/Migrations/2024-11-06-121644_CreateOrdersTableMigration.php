<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "order_id" => [
                "type" => "INT",
                "auto_increment" => true,
                "unsigned" => true
            ],
            "user_id" => [
                "type" => "INT",
                "null" => false,
                "unsigned" => true
            ],
            "coupon_id" => [
                "type" => "INT",
                "null" => true,
                "unsigned" => true
            ],
            "total_amount" => [
                "type" => "DECIMAL",
                "constraint" => "10,2",
                "null" => false
            ],
            "status" => [
                "type" => "ENUM",
                "constraint" => ["pending", "completed", "failed"],
                "null" => false
            ],
            "created_at timestamp default current_timestamp" 
        ]);

        $this->forge->addPrimaryKey("order_id");
        $this->forge->addForeignKey("user_id", "users", "user_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("coupon_id", "coupons", "coupon_id", "SET NULL", "CASCADE");
        $this->forge->createTable("orders");
    }

    public function down()
    {
        $this->forge->dropTable("orders");
    }
}
