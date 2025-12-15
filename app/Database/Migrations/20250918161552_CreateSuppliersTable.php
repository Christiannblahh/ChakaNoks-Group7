<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSuppliersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'supplier_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'supplier_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'contact_person' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'contact_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'unique'     => true,
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'state' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'postal_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'country' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'supplier_type' => [
                'type'       => 'ENUM',
                'constraint' => ['Food', 'Equipment', 'Packaging', 'Other'],
                'default'    => 'Food',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Active', 'Inactive', 'Suspended'],
                'default'    => 'Active',
            ],
            'rating' => [
                'type'    => 'DECIMAL',
                'constraint' => '3,2',
                'default' => 5.00,
                'null'    => true,
            ],
            'total_orders' => [
                'type'    => 'INT',
                'default' => 0,
                'null'    => true,
            ],
            'on_time_delivery_rate' => [
                'type'    => 'DECIMAL',
                'constraint' => '5,2',
                'null'    => true,
            ],
            'quality_rating' => [
                'type'    => 'DECIMAL',
                'constraint' => '3,2',
                'null'    => true,
            ],
            'terms' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addKey('supplier_id', true);
        $this->forge->createTable('suppliers');
    }

    public function down()
    {
        $this->forge->dropTable('suppliers');
    }
}
