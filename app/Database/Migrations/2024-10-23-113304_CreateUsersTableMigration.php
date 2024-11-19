<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "user_id" => [
                "type"=> "INT",
                "auto_increment" => true,
                "unsigned"=> true
            ],
            "name" => [
                "type"=> "VARCHAR",
                "constraint" => "100",
            ],
            "email" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "null"=> false,
                "unique" => true
            ],
            "password" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "null"=> false
            ],
            "address" => [
                "type" => "TEXT",
            ],
            "role" => [
                "type" => "ENUM",
                "constraint" => ["admin", "customer"],
                "null"=> false,
                "default" => "customer"
            ],
            "created_at timestamp default current_timestamp" 
        ]);

        $this->forge->addPrimaryKey("user_id");
        $this->forge->createTable("users");
    }

    public function down()
    {
        $this->forge->dropTable("users");
    }
}
