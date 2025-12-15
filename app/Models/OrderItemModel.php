<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'order_item_id';
    protected $allowedFields = [
        'order_id',
        'item_name',
        'quantity',
        'unit_price',
        'total_price'
    ];

    /**
     * Get items for an order
     */
    public function getByOrder($orderId)
    {
        return $this->where('order_id', $orderId)->findAll();
    }

    /**
     * Add order item
     */
    public function addItem($data)
    {
        return $this->insert($data);
    }

    /**
     * Get order total
     */
    public function getOrderTotal($orderId)
    {
        $result = $this->select('SUM(total_price) as total')
                       ->where('order_id', $orderId)
                       ->first();
        return $result['total'] ?? 0;
    }
}
