<?php

namespace App\Models;

use CodeIgniter\Model;

class FranchiseApplicationModel extends Model
{
    protected $table = 'franchise_applications';
    protected $primaryKey = 'application_id';
    protected $allowedFields = [
        'applicant_name',
        'business_name',
        'location',
        'contact_number',
        'status',
    ];
    protected $useTimestamps = false;

    public function getPending()
    {
        return $this->where('status', 'Pending')
                    ->orderBy('application_id', 'DESC')
                    ->findAll();
    }
}
