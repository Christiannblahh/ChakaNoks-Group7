<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';
    protected $allowedFields = [
        'supplier_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'supplier_type',
        'status',
        'rating',
        'total_orders',
        'on_time_delivery_rate',
        'quality_rating',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all active suppliers
     */
    public function getActive()
    {
        return $this->where('status', 'Active')
                    ->orderBy('supplier_name', 'ASC')
                    ->findAll();
    }

    /**
     * Get supplier with statistics
     */
    public function getWithStats($supplierId)
    {
        $db = \Config\Database::connect();
        return $db->table('suppliers s')
                  ->select('s.*, COUNT(po.order_id) as total_orders, AVG(d.status = "Delivered") as on_time_rate')
                  ->join('purchase_orders po', 's.supplier_id = po.supplier_id', 'left')
                  ->join('deliveries d', 'po.order_id = d.order_id', 'left')
                  ->where('s.supplier_id', $supplierId)
                  ->groupBy('s.supplier_id')
                  ->get()
                  ->getRowArray();
    }

    /**
     * Update supplier performance rating
     */
    public function updateRating($supplierId, $rating)
    {
        return $this->update($supplierId, ['rating' => $rating]);
    }

    /**
     * Get suppliers by type
     */
    public function getByType($type)
    {
        return $this->where('supplier_type', $type)
                    ->where('status', 'Active')
                    ->orderBy('rating', 'DESC')
                    ->findAll();
    }

    /**
     * Search suppliers
     */
    public function search($keyword)
    {
        return $this->like('supplier_name', $keyword)
                    ->orLike('contact_person', $keyword)
                    ->orLike('email', $keyword)
                    ->where('status', 'Active')
                    ->findAll();
    }
}
