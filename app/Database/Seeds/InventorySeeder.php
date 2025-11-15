<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class InventorySeeder extends Seeder
{
    public function run()
    {
        $inventoryItems = [
            [
                'branch_id' => 1,
                'item_name' => 'Grilled Chicken Breast',
                'item_description' => 'Premium grilled chicken breast cuts',
                'unit' => 'kg',
                'quantity' => 150,
                'reorder_level' => 20,
                'expiry_date' => date('Y-m-d', strtotime('+6 months')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Chicken Wings',
                'item_description' => 'Fresh chicken wings for frying',
                'unit' => 'kg',
                'quantity' => 25,
                'reorder_level' => 15,
                'expiry_date' => date('Y-m-d', strtotime('+4 months')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Chicken Nuggets',
                'item_description' => 'Frozen chicken nuggets',
                'unit' => 'kg',
                'quantity' => 0,
                'reorder_level' => 10,
                'expiry_date' => date('Y-m-d', strtotime('+8 months')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Chicken Tenders',
                'item_description' => 'Breaded chicken tenders',
                'unit' => 'kg',
                'quantity' => 80,
                'reorder_level' => 25,
                'expiry_date' => date('Y-m-d', strtotime('+5 months')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Chicken Strips',
                'item_description' => 'Seasoned chicken strips',
                'unit' => 'kg',
                'quantity' => 45,
                'reorder_level' => 20,
                'expiry_date' => date('Y-m-d', strtotime('+3 months')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
        ];

        // Insert inventory items
        foreach ($inventoryItems as $item) {
            $this->db->table('inventory')->insert($item);
        }
    }
}
