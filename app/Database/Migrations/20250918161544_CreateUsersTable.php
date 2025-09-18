<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'fname' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'lname' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'unique'     => true,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => [
                    'Branch Manager', 
                    'Inventory Staff', 
                    'Central Admin', 
                    'Supplier', 
                    'Logistics Coordinator', 
                    'Franchise Manager', 
                    'System Admin'
                ],
            ],
            'branch_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->addForeignKey('branch_id', 'branches', 'branch_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
