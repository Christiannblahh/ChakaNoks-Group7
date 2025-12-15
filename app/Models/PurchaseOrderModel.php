<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table = 'purchase_orders';
    protected $primaryKey = 'order_id';
    protected $allowedFields = [
        'request_id',
        'supplier_id',
        'approved_by',
        'order_date',
        'status',
        'expected_delivery',
        'total_amount',
        'notes'
    ];
    protected $useTimestamps = false;

    /**
     * Get purchase order with related data
     */
    public function getWithDetails($orderId)
    {
        $db = \Config\Database::connect();
        return $db->table('purchase_orders po')
                  ->select('po.*, s.supplier_name, s.email as supplier_email, u.email as approved_by_email')
                  ->join('suppliers s', 'po.supplier_id = s.supplier_id', 'left')
                  ->join('users u', 'po.approved_by = u.user_id', 'left')
                  ->where('po.order_id', $orderId)
                  ->get()
                  ->getRowArray();
    }

    /**
     * Get pending orders
     */
    public function getPendingOrders()
    {
        return $this->where('status', 'Pending')
                    ->orderBy('order_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get orders by supplier
     */
    public function getBySupplier($supplierId)
    {
        return $this->where('supplier_id', $supplierId)
                    ->orderBy('order_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get orders by status
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)
                    ->orderBy('order_date', 'DESC')
                    ->findAll();
    }

    /**
     * Update order status
     */
    public function updateStatus($orderId, $status)
    {
        return $this->update($orderId, ['status' => $status]);
    }

    /**
     * Get overdue deliveries
     */
    public function getOverdueDeliveries()
    {
        return $this->select('po.*, s.supplier_name')
                    ->join('suppliers s', 'po.supplier_id = s.supplier_id', 'left')
                    ->where('po.expected_delivery <', date('Y-m-d'))
                    ->where('po.status !=', 'Delivered')
                    ->findAll();
    }
}
