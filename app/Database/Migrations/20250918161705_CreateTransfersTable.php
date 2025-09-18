<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTransfersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'transfer_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'from_branch_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'to_branch_id' => [
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
            'transfer_date' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'approved_by' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey('transfer_id', true);
        $this->forge->addForeignKey('from_branch_id', 'branches', 'branch_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('to_branch_id', 'branches', 'branch_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('transfers');
    }

    public function down()
    {
        $this->forge->dropTable('transfers');
    }
}
