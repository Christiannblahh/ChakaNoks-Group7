<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockRecordTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'item_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'action' => [
                'type' => 'VARCHAR',
                'constraint' => 32,
            ],
            'details' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'datetime' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('stock_records');
    }

    public function down()
    {
        $this->forge->dropTable('stock_records');
    }
}
