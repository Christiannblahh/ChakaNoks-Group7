<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // First, create a supplier if not exists
        $supplier = $db->table('suppliers')
                       ->where('supplier_name', 'Test Supplier Co.')
                       ->get()
                       ->getRow();
        
        if (!$supplier) {
            $db->table('suppliers')->insert([
                'supplier_name'       => 'Test Supplier Co.',
                'contact_person'      => 'John Doe',
                'email'               => 'test.supplier@example.com',
                'phone'               => '555-1234',
                'address'             => '123 Supply St',
                'city'                => 'New York',
                'state'               => 'NY',
                'postal_code'         => '10001',
                'country'             => 'USA',
                'supplier_type'       => 'Raw Materials',
                'status'              => 'Active',
                'rating'              => 4.5,
                'on_time_delivery_rate' => 0.95,
                'quality_rating'      => 4.6,
            ]);
            $supplierId = $db->insertID();
        } else {
            $supplierId = $supplier->supplier_id;
        }

        // Create a purchase request if not exists
        $request = $db->table('purchase_requests')
                      ->where('request_id', 1)
                      ->get()
                      ->getRow();
        
        if (!$request) {
            $branch = $db->table('branches')
                         ->where('branch_id', 1)
                         ->get()
                         ->getRow();
            
            if (!$branch) {
                $db->table('branches')->insert([
                    'branch_name'    => 'Main Branch',
                    'location'       => 'HQ',
                    'contact_number' => '000-000-0000',
                ]);
            }

            $db->table('purchase_requests')->insert([
                'branch_id'          => 1,
                'requested_by'       => 1,
                'status'             => 'Approved',
            ]);
            $requestId = $db->insertID();
        } else {
            $requestId = $request->request_id;
        }

        // Create purchase orders for testing
        $orders = [
            [
                'request_id'        => $requestId,
                'supplier_id'       => $supplierId,
                'approved_by'       => 1,
                'status'            => 'Pending',
            ],
            [
                'request_id'        => $requestId,
                'supplier_id'       => $supplierId,
                'approved_by'       => 1,
                'status'            => 'Pending',
            ],
            [
                'request_id'        => $requestId,
                'supplier_id'       => $supplierId,
                'approved_by'       => 1,
                'status'            => 'Pending',
            ],
        ];

        foreach ($orders as $order) {
            $db->table('purchase_orders')->insert($order);
        }
    }
}
