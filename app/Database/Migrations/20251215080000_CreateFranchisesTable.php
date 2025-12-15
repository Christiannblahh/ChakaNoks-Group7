<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFranchisesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'franchise_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'branch_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'owner_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'agreement_start' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'agreement_end' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'royalty_type' => [
                'type'       => 'ENUM',
                'constraint' => ['percent', 'fixed'],
                'default'    => 'percent',
            ],
            'royalty_rate' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Active', 'Inactive', 'Terminated'],
                'default'    => 'Active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('franchise_id', true);
        $this->forge->createTable('franchises');
    }

    public function down()
    {
        $this->forge->dropTable('franchises');
    }
}
