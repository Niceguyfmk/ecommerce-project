<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateImagesTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'image_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'image_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'image_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true, 
            ],
            'created_at timestamp default current_timestamp'
        ]);
        
        $this->forge->addPrimaryKey('image_id');
        $this->forge->addForeignKey('product_id', 'products', 'product_id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('images');
    }

    public function down()
    {
        $this->forge->dropTable('images');
    }
}
