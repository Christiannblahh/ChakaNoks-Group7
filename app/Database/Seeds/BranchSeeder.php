<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run()
    {
        $branches = [
            [
                'branch_name'    => 'Main Branch',
                'location'       => 'HQ',
                'contact_number' => '000-000-0000',
            ],
            [
                'branch_name'    => 'North Branch',
                'location'       => 'North District',
                'contact_number' => '111-111-1111',
            ],
            [
                'branch_name'    => 'South Branch',
                'location'       => 'South District',
                'contact_number' => '222-222-2222',
            ],
            [
                'branch_name'    => 'East Branch',
                'location'       => 'East District',
                'contact_number' => '333-333-3333',
            ],
            [
                'branch_name'    => 'West Branch',
                'location'       => 'West District',
                'contact_number' => '444-444-4444',
            ],
        ];

        // Upsert branches by unique branch_name
        foreach ($branches as $branch) {
            $exists = $this->db->table('branches')->where('branch_name', $branch['branch_name'])->countAllResults();
            if ($exists) {
                $this->db->table('branches')->where('branch_name', $branch['branch_name'])->update($branch);
            } else {
                $this->db->table('branches')->insert($branch);
            }
        }
    }
}
