<?php

namespace App\Models;

use CodeIgniter\Model;

class TransferModel extends Model
{
    protected $table = 'transfers';
    protected $primaryKey = 'transfer_id';
    protected $allowedFields = [
        'from_branch_id',
        'to_branch_id',
        'item_name',
        'quantity',
        'transfer_date',
        'approved_by'
    ];
    protected $useTimestamps = false;

    /**
     * Create a new transfer
     */
    public function createTransfer($data)
    {
        return $this->insert($data);
    }

    /**
     * Get all transfers
     */
    public function getAllTransfers()
    {
        return $this->orderBy('transfer_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get recent transfers (limit to 10 most recent)
     */
    public function getRecentTransfers($limit = 10)
    {
        return $this->orderBy('transfer_date', 'DESC')
                    ->findAll($limit);
    }

    /**
     * Get transfers by branch (either from or to)
     */
    public function getTransfersByBranch($branchId)
    {
        return $this->where('from_branch_id', $branchId)
                    ->orWhere('to_branch_id', $branchId)
                    ->orderBy('transfer_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get transfers with branch names
     */
    public function getTransfersWithBranchNames()
    {
        $db = \Config\Database::connect();
        return $db->table('transfers t')
                  ->select('t.*, fb.branch_name as from_branch_name, tb.branch_name as to_branch_name')
                  ->join('branches fb', 't.from_branch_id = fb.branch_id', 'left')
                  ->join('branches tb', 't.to_branch_id = tb.branch_id', 'left')
                  ->orderBy('t.transfer_date', 'DESC')
                  ->get()
                  ->getResultArray();
    }
}
