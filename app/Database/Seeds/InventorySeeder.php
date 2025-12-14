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
                'item_name' => 'Whole Chicken',
                'item_description' => 'Fresh whole chicken',
                'unit' => 'pcs',
                'quantity' => 50,
                'reorder_level' => 10,
                'expiry_date' => date('Y-m-d', strtotime('+7 days')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Charcoal',
                'item_description' => 'Charcoal for grilling',
                'unit' => 'kg',
                'quantity' => 100,
                'reorder_level' => 20,
                'expiry_date' => date('Y-m-d', strtotime('+24 months')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Chicken Breast',
                'item_description' => 'Boneless chicken breast',
                'unit' => 'kg',
                'quantity' => 30,
                'reorder_level' => 5,
                'expiry_date' => date('Y-m-d', strtotime('+7 days')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Chicken Wings',
                'item_description' => 'Fresh chicken wings',
                'unit' => 'kg',
                'quantity' => 40,
                'reorder_level' => 10,
                'expiry_date' => date('Y-m-d', strtotime('+7 days')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Flour',
                'item_description' => 'All-purpose flour',
                'unit' => 'kg',
                'quantity' => 100,
                'reorder_level' => 20,
                'expiry_date' => date('Y-m-d', strtotime('+12 months')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Eggs',
                'item_description' => 'Fresh chicken eggs',
                'unit' => 'pcs',
                'quantity' => 200,
                'reorder_level' => 40,
                'expiry_date' => date('Y-m-d', strtotime('+21 days')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Ground Black Pepper',
                'item_description' => 'Finely ground black pepper',
                'unit' => 'kg',
                'quantity' => 10,
                'reorder_level' => 2,
                'expiry_date' => date('Y-m-d', strtotime('+24 months')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Salt',
                'item_description' => 'Refined table salt',
                'unit' => 'kg',
                'quantity' => 25,
                'reorder_level' => 5,
                'expiry_date' => date('Y-m-d', strtotime('+36 months')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Baking Soda',
                'item_description' => 'Baking soda for cooking',
                'unit' => 'kg',
                'quantity' => 8,
                'reorder_level' => 2,
                'expiry_date' => date('Y-m-d', strtotime('+24 months')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Butter',
                'item_description' => 'Creamy butter',
                'unit' => 'kg',
                'quantity' => 15,
                'reorder_level' => 3,
                'expiry_date' => date('Y-m-d', strtotime('+6 months')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Hot Sauce',
                'item_description' => 'Spicy hot sauce',
                'unit' => 'liter',
                'quantity' => 10,
                'reorder_level' => 2,
                'expiry_date' => date('Y-m-d', strtotime('+12 months')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Vegetable Oil',
                'item_description' => 'High quality vegetable oil',
                'unit' => 'liter',
                'quantity' => 10,
                'reorder_level' => 2,
                'expiry_date' => date('Y-m-d', strtotime('+12 months')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Mayonnaise',
                'item_description' => 'Creamy mayonnaise',
                'unit' => 'liter',
                'quantity' => 12,
                'reorder_level' => 3,
                'expiry_date' => date('Y-m-d', strtotime('+6 months')),
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'branch_id' => 1,
                'item_name' => 'Chives',
                'item_description' => 'Fresh chives for garnish',
                'unit' => 'kg',
                'quantity' => 5,
                'reorder_level' => 1,
                'expiry_date' => date('Y-m-d', strtotime('+14 days')),
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
