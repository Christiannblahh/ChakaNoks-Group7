<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFranchiseApplicationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'application_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'applicant_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'business_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'contact_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Pending', 'Approved', 'Denied'],
                'default'    => 'Pending',
            ],
        ]);
        $this->forge->addKey('application_id', true);
        $this->forge->createTable('franchise_applications');
    }

    public function down()
    {
        $this->forge->dropTable('franchise_applications');
    }
}
