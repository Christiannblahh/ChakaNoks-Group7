<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'fname'     => 'Alice',
                'lname'     => 'Admin',
                'email'     => 'central.admin@example.com',
                'password'  => password_hash('Password123!', PASSWORD_DEFAULT),
                'role'      => 'Central Admin',
                'branch_id' => null,
            ],
            [
                'fname'     => 'Bob',
                'lname'     => 'System',
                'email'     => 'system.admin@example.com',
                'password'  => password_hash('Password123!', PASSWORD_DEFAULT),
                'role'      => 'System Admin',
                'branch_id' => null,
            ],
            [
                'fname'     => 'Bella',
                'lname'     => 'Branch',
                'email'     => 'branch.manager@example.com',
                'password'  => password_hash('Password123!', PASSWORD_DEFAULT),
                'role'      => 'Branch Manager',
                'branch_id' => 1,
            ],
            [
                'fname'     => 'Ivan',
                'lname'     => 'Inventory',
                'email'     => 'inventory.staff@example.com',
                'password'  => password_hash('Password123!', PASSWORD_DEFAULT),
                'role'      => 'Inventory Staff',
                'branch_id' => 1,
            ],
            [
                'fname'     => 'Liam',
                'lname'     => 'Logistics',
                'email'     => 'logistics.coord@example.com',
                'password'  => pasgit commit -m "Your commit message"
sword_hash('Password123!', PASSWORD_DEFAULT),
                'role'      => 'Logistics Coordinator',
                'branch_id' => null,
            ],
            [
                'fname'     => 'Fiona',
                'lname'     => 'Franchise',
                'email'     => 'franchise.manager@example.com',
                'password'  => password_hash('Password123!', PASSWORD_DEFAULT),
                'role'      => 'Franchise Manager',
                'branch_id' => null,
            ],
            [
                'fname'     => 'Sam',
                'lname'     => 'Supplier',
                'email'     => 'supplier@example.com',
                'password'  => password_hash('Password123!', PASSWORD_DEFAULT),
                'role'      => 'Supplier',
                'branch_id' => null,
            ],
        ];

        // Ensure branch #1 exists for branch-based roles
        $branchExists = $this->db->table('branches')->where('branch_id', 1)->countAllResults();
        if (!$branchExists) {
            $this->db->table('branches')->insert([
                'branch_name'    => 'Main Branch',
                'location'       => 'HQ',
                'contact_number' => '000-000-0000',
            ]);
        }

        // Upsert users by unique email
        foreach ($users as $user) {
            $exists = $this->db->table('users')->where('email', $user['email'])->countAllResults();
            if ($exists) {
                $this->db->table('users')->where('email', $user['email'])->update($user);
            } else {
                $this->db->table('users')->insert($user);
            }
        }
    }
}
