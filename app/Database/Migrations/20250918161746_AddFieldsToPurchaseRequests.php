<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToPurchaseRequests extends Migration
{
    public function up()
    {
        // Add missing columns to purchase_requests if they don't exist
        $fields = [
            'approved_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
                'after'    => 'status'
            ],
            'approval_date' => [
                'type'     => 'DATETIME',
                'null'     => true,
                'after'    => 'approved_by'
            ],
            'notes' => [
                'type'   => 'TEXT',
                'null'   => true,
                'after'  => 'approval_date'
            ]
        ];

        foreach ($fields as $fieldName => $fieldConfig) {
            if (!$this->db->fieldExists($fieldName, 'purchase_requests')) {
                $this->forge->addColumn('purchase_requests', [$fieldName => $fieldConfig]);
            }
        }
    }

    public function down()
    {
        // Remove the added columns
        if ($this->db->fieldExists('approved_by', 'purchase_requests')) {
            $this->forge->dropColumn('purchase_requests', 'approved_by');
        }
        if ($this->db->fieldExists('approval_date', 'purchase_requests')) {
            $this->forge->dropColumn('purchase_requests', 'approval_date');
        }
        if ($this->db->fieldExists('notes', 'purchase_requests')) {
            $this->forge->dropColumn('purchase_requests', 'notes');
        }
    }
}
