<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingColumnsToPurchaseRequestItems extends Migration
{
    public function up()
    {
        // Add missing columns to purchase_request_items table
        $fields = [
            'description' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'item_name'
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => 'pcs',
                'after'      => 'description'
            ],
            'estimated_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'default'    => 0.00,
                'after'      => 'unit'
            ],
            'notes' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'estimated_cost'
            ]
        ];

        foreach ($fields as $fieldName => $fieldConfig) {
            if (!$this->db->fieldExists($fieldName, 'purchase_request_items')) {
                $this->forge->addColumn('purchase_request_items', [$fieldName => $fieldConfig]);
            }
        }
    }

    public function down()
    {
        // Remove the added columns
        $columns = ['description', 'unit', 'estimated_cost', 'notes'];
        foreach ($columns as $column) {
            if ($this->db->fieldExists($column, 'purchase_request_items')) {
                $this->forge->dropColumn('purchase_request_items', $column);
            }
        }
    }
}
