<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateRoyaltyPaymentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'payment_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'application_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'payment_date' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('payment_id', true);
        $this->forge->addForeignKey('application_id', 'franchise_applications', 'application_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('royalty_payments');
    }

    public function down()
    {
        $this->forge->dropTable('royalty_payments');
    }
}
