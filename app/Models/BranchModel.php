<?php

namespace App\Models;

use CodeIgniter\Model;

class BranchModel extends Model
{
    protected $table = 'branches';
    protected $primaryKey = 'branch_id';
    protected $allowedFields = [
        'branch_name',
        'location',
        'contact_number'
    ];

    /**
     * Get branch by ID
     */
    public function getBranchById($branchId)
    {
        return $this->find($branchId);
    }

    /**
     * Get branch by name
     */
    public function getBranchByName($branchName)
    {
        return $this->where('branch_name', $branchName)
                    ->first();
    }

    /**
     * Get all branches
     */
    public function getAllBranches()
    {
        return $this->findAll();
    }
}
