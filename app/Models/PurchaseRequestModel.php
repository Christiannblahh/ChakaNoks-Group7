<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseRequestModel extends Model
{
    protected $table = 'purchase_requests';
    protected $primaryKey = 'request_id';
    protected $allowedFields = [
        'branch_id',
        'requested_by',
        'request_date',
        'status',
        'updated_at',
        'approved_by',
        'approval_date',
        'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'request_date';
    protected $updatedField = 'updated_at';

    /**
     * Get all purchase requests for a branch
     */
    public function getByBranch($branchId)
    {
        return $this->where('branch_id', $branchId)
                    ->orderBy('request_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get all pending requests for approval
     */
    public function getPendingRequests()
    {
        return $this->where('status', 'Pending')
                    ->orderBy('request_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get request with items
     */
    public function getRequestWithItems($requestId)
    {
        $db = \Config\Database::connect();
        return $db->table('purchase_requests pr')
                  ->select('pr.*, u.email as requested_by_email, b.branch_name')
                  ->join('users u', 'pr.requested_by = u.user_id', 'left')
                  ->join('branches b', 'pr.branch_id = b.branch_id', 'left')
                  ->where('pr.request_id', $requestId)
                  ->get()
                  ->getRowArray();
    }

    /**
     * Update request status
     */
    public function updateStatus($requestId, $status, $approvedBy = null)
    {
        return $this->update($requestId, [
            'status' => $status,
            'approved_by' => $approvedBy,
            'approval_date' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get requests by status
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)
                    ->orderBy('request_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get total pending requests count
     */
    public function getPendingCount()
    {
        return $this->where('status', 'Pending')->countAllResults();
    }
}
