<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreatePurchaseRequestsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'request_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'branch_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'requested_by' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'request_date' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Pending', 'Approved', 'Denied', 'Ordered'],
                'default'    => 'Pending',
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('request_id', true);
        $this->forge->addForeignKey('branch_id', 'branches', 'branch_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('requested_by', 'users', 'user_id', 'CASCADE', 'CASCADE');

        // Pass "false" for $ifNotExists so it always creates clean
        $this->forge->createTable('purchase_requests', true);
    }

    public function down()
    {
        $this->forge->dropTable('purchase_requests', true);
    }
}
