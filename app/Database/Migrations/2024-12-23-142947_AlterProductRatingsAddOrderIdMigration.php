<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterProductRatingsAddOrderIdMigration extends Migration
{
    public function up()
    {
        // Add the `order_id` column
        $fields = [
            "order_id" => [
                "type" => "INT",
                "unsigned" => true,
                "null" => true, // to avoid issues with existing data
            ],
        ];

        $this->forge->addColumn("product_ratings", $fields);

        // Add the foreign key constraint
        $this->db->query("ALTER TABLE `product_ratings` ADD CONSTRAINT `fk_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders`(`order_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    }

    public function down()
    {
        // Drop the foreign key constraint
        $this->db->query("ALTER TABLE `product_ratings` DROP FOREIGN KEY `fk_order_id`");

        // Drop the `order_id` column
        $this->forge->dropColumn("product_ratings", "order_id");
    }
}
