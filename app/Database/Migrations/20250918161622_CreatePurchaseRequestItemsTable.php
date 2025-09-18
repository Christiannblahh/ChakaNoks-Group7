<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePurchaseRequestItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'request_item_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'request_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'quantity_requested' => [
                'type' => 'INT',
            ],
        ]);
        $this->forge->addKey('request_item_id', true);
        $this->forge->addForeignKey('request_id', 'purchase_requests', 'request_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('purchase_request_items');
    }

    public function down()
    {
        $this->forge->dropTable('purchase_request_items');
    }
}
