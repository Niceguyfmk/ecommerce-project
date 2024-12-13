<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductRatingTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "rating_id" => [
                "type" => "INT",
                "auto_increment" => true,
                "unsigned" => true,
            ],
            "product_id" => [
                "type" => "INT",
                "unsigned" => true,
                "null" => false,
            ],
            "user_id" => [
                "type" => "INT",
                "unsigned" => true,
                "null" => false,
            ],
            "rating" => [
                "type" => "TINYINT",
                "constraint" => 1,
                "null" => false,
            ],
            "comment" => [
                "type" => "TEXT",
                "null" => true,
            ],
            "created_at timestamp default current_timestamp",
            "updated_at timestamp default current_timestamp",
        ]);

        $this->forge->addPrimaryKey("rating_id");
        $this->forge->addForeignKey("product_id", "products", "product_id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("user_id", "users", "user_id", "CASCADE", "CASCADE");
        $this->forge->createTable("product_ratings");
    }

    public function down()
    {
        $this->forge->dropTable("product_ratings");
    }
}
