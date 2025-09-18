<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'order_item_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'order_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'quantity_ordered' => [
                'type' => 'INT',
            ],
            'quantity_received' => [
                'type' => 'INT',
                'default' => 0,
            ],
        ]);
        $this->forge->addKey('order_item_id', true);
        $this->forge->addForeignKey('order_id', 'purchase_orders', 'order_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('order_items');
    }

    public function down()
    {
        $this->forge->dropTable('order_items');
    }
}
