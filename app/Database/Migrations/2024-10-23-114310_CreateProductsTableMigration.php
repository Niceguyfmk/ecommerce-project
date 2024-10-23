<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type"=> "INT",
                "auto_increment" => true,
                "unsigned"=> true
            ],
            "name" => [
                "type"=> "VARCHAR",
                "constraint" => "100",
                "null" => false
            ],
            "price" => [
                "type"=> "decimal",
                "constraint" => "10,2",
                "null" => false
            ],
            "size" => [
                "type" => "VARCHAR",
                "constraint" => "50",
                "null"=> false,
            ],
            "color" => [
                "type" => "VARCHAR",
                "constraint" => "50",
                "null"=> false
            ],
            "brand" => [
                "type" => "VARCHAR",
                "constraint" => "50",
                "null"=> false
            ],
            "description" => [
                "type" => "TEXT",
                "null"=> false
            ],
            "image" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "null"=> true
            ],
            "created_at datetime default current_timestamp" 
        ]);

        $this->forge->addPrimaryKey("id");
        $this->forge->createTable("products");
    }

    public function down()
    {
        $this->forge->dropTable("products");
    }
}
