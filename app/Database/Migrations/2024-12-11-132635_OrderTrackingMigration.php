<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OrderTrackingMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'order_tracking_id' => [
                'type' => 'INT',
                'auto_increment' => true,
                'unsigned' => true,
            ],
            'order_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'order_tracking_status' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'status_time' => [
                'type' => 'DATETIME',
                'null' => true,  // Optional field
            ],
            "created_at timestamp default current_timestamp" 
        ]);

        // Add foreign key constraint for order_id
        $this->forge->addForeignKey('order_id', 'orders', 'order_id', 'CASCADE', 'CASCADE');

        // Add the primary key
        $this->forge->addPrimaryKey('order_tracking_id');

        // Create the table
        $this->forge->createTable('order_tracking');
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('order_tracking');
    }
}
