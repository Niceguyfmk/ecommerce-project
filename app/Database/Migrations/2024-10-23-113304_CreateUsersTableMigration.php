<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTableMigration extends Migration
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
            "email" => [
                "type" => "VARCHAR",
                "constraint" => "50",
                "null"=> false,
                "unique" => true
            ],
            "password" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "null"=> false
            ],
            "phone_no" => [
                "type" => "VARCHAR",
                "constraint" => "20",
                "null"=> false
            ],
            "role" => [
                "type" => "ENUM",
                "constraint" => ["admin", "customer"],
                "null"=> false,
                "default" => "customer"
            ],
            "created_at datetime default current_timestamp" 
        ]);

        $this->forge->addPrimaryKey("id");
        $this->forge->createTable("users");
    }

    public function down()
    {
        $this->forge->dropTable("users");
    }
}
