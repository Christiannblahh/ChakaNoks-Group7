<?php

namespace App\Models;

use CodeIgniter\Model;

class FranchiseSupplyModel extends Model
{
    protected $table = 'franchise_supplies';
    protected $primaryKey = 'franchise_supply_id';
    protected $allowedFields = [
        'application_id',
        'item_name',
        'quantity',
        'supply_date',
    ];
    protected $useTimestamps = false;

    public function getByApplication($applicationId)
    {
        return $this->where('application_id', $applicationId)
                    ->orderBy('supply_date', 'DESC')
                    ->findAll();
    }
}
