<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseRequestItemModel extends Model
{
    protected $table = 'purchase_request_items';
    protected $primaryKey = 'item_id';
    protected $allowedFields = [
        'request_id',
        'item_name',
        'description',
        'quantity_requested',
        'unit',
        'estimated_cost',
        'notes'
    ];

    /**
     * Get all items for a request
     */
    public function getByRequest($requestId)
    {
        return $this->where('request_id', $requestId)->findAll();
    }

    /**
     * Add item to request
     */
    public function addItem($data)
    {
        return $this->insert($data);
    }

    /**
     * Update item
     */
    public function updateItem($itemId, $data)
    {
        return $this->update($itemId, $data);
    }

    /**
     * Delete item
     */
    public function deleteItem($itemId)
    {
        return $this->delete($itemId);
    }

    /**
     * Get items with total cost
     */
    public function getItemsWithTotal($requestId)
    {
        return $this->select('*, (quantity_requested * estimated_cost) as item_total')
                    ->where('request_id', $requestId)
                    ->findAll();
    }
}
