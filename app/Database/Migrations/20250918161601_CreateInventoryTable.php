<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'inventory_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'branch_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'item_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'quantity' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'reorder_level' => [
                'type'    => 'INT',
                'default' => 10,
            ],
            'expiry_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);
        $this->forge->addKey('inventory_id', true);
        $this->forge->addForeignKey('branch_id', 'branches', 'branch_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('inventory');
    }

    public function down()
    {
        $this->forge->dropTable('inventory');
    }
}
