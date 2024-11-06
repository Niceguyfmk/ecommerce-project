<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreareAdminTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "admin_id" => [
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
                "constraint" => "255    ",
                "null"=> false,
                "unique" => true
            ],
            "password" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "null"=> false
            ],
            "role" => [
                "type" => "ENUM",
                "constraint" => ["admin", "manager", "sales_rep"],
                "null"=> false,
                "default" => "admin"
            ],
            "created_at timestamp default current_timestamp" 
        ]);

        $this->forge->addPrimaryKey("admin_id");
        $this->forge->createTable("admin_users");
    }

    public function down()
    {
        $this->forge->dropTable("admin_users");
    }
}
