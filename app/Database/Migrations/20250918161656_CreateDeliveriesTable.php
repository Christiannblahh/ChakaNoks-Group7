<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDeliveriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'delivery_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'order_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'logistics_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'scheduled_date' => [
                'type' => 'DATE',
            ],
            'delivered_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Scheduled', 'In Transit', 'Delivered'],
                'default'    => 'Scheduled',
            ],
        ]);
        $this->forge->addKey('delivery_id', true);
        $this->forge->addForeignKey('order_id', 'purchase_orders', 'order_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('logistics_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('deliveries');
    }

    public function down()
    {
        $this->forge->dropTable('deliveries');
    }
}
