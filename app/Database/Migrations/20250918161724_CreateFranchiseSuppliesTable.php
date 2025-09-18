<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFranchiseSuppliesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'franchise_supply_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'application_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'quantity' => [
                'type' => 'INT',
            ],
            'supply_date' => [
                'type' => 'DATE',
            ],
        ]);
        $this->forge->addKey('franchise_supply_id', true);
        $this->forge->addForeignKey('application_id', 'franchise_applications', 'application_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('franchise_supplies');
    }

    public function down()
    {
        $this->forge->dropTable('franchise_supplies');
    }
}
