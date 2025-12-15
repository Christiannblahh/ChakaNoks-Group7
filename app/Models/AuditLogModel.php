<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'log_id';
    protected $allowedFields = [
        'action',
        'description',
        'user_id',
        'timestamp',
        'ip_address'
    ];

    /**
     * Get recent audit logs
     */
    public function getRecent($limit = 50)
    {
        return $this->select('al.*, u.email as user_email')
                    ->join('users u', 'al.user_id = u.user_id', 'left')
                    ->orderBy('al.timestamp', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get logs by user
     */
    public function getByUser($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('timestamp', 'DESC')
                    ->findAll();
    }

    /**
     * Get logs by action
     */
    public function getByAction($action)
    {
        return $this->where('action', $action)
                    ->orderBy('timestamp', 'DESC')
                    ->findAll();
    }

    /**
     * Get logs between dates
     */
    public function getByDateRange($startDate, $endDate)
    {
        return $this->where('DATE(timestamp) >=', $startDate)
                    ->where('DATE(timestamp) <=', $endDate)
                    ->orderBy('timestamp', 'DESC')
                    ->findAll();
    }
}
