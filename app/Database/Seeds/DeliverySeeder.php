<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DeliverySeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Create test deliveries for existing purchase orders
        $orders = $db->table('purchase_orders')
                     ->limit(3)
                     ->get()
                     ->getResultArray();
        
        if (count($orders) > 0) {
            $now = date('Y-m-d H:i:s');
            $deliveries = [];
            
            for ($i = 0; $i < count($orders); $i++) {
                $orderId = $orders[$i]['order_id'];
                
                // Check if delivery already exists
                $existing = $db->table('deliveries')
                               ->where('order_id', $orderId)
                               ->get()
                               ->getRow();
                
                if (!$existing) {
                    $deliveries[] = [
                        'order_id' => $orderId,
                        'logistics_id' => 5, // Logistics Coordinator from UserSeeder
                        'scheduled_date' => date('Y-m-d H:i:s', strtotime('+' . ($i + 1) . ' days')),
                        'status' => $i === 0 ? 'Scheduled' : ($i === 1 ? 'In Transit' : 'Delivered'),
                        'delivered_at' => $i === 2 ? $now : null
                    ];
                }
            }
            
            if (count($deliveries) > 0) {
                $db->table('deliveries')->insertBatch($deliveries);
            }
        }
    }
}
