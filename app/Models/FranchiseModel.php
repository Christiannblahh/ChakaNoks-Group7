<?php

namespace App\Models;

use CodeIgniter\Model;

class FranchiseModel extends Model
{
    protected $table = 'franchises';
    protected $primaryKey = 'franchise_id';
    protected $allowedFields = [
        'branch_id',
        'owner_name',
        'agreement_start',
        'agreement_end',
        'royalty_type',
        'royalty_rate',
        'status',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
