<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreatePurchaseOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'order_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'request_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'supplier_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'approved_by' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'order_date' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Pending', 'Shipped', 'Delivered', 'Cancelled'],
                'default'    => 'Pending',
            ],
        ]);
        $this->forge->addKey('order_id', true);
        $this->forge->addForeignKey('request_id', 'purchase_requests', 'request_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'supplier_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('purchase_orders');
    }

    public function down()
    {
        $this->forge->dropTable('purchase_orders');
    }
}
