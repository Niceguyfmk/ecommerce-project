<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCartTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "cart_id" => [
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
            "created_at timestamp default current_timestamp" 
        ]);

        $this->forge->addPrimaryKey("cart_id");
        $this->forge->addForeignKey("user_id", "users", "user_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("coupon_id", "coupons", "coupon_id", "SET NULL", "CASCADE");
        $this->forge->createTable("cart");
    }

    public function down()
    {
        $this->forge->dropTable("cart");
    }
}
