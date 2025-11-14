<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'inventory_id';
    protected $allowedFields = [
        'branch_id',
        'item_name',
        'item_description',
        'unit',
        'quantity',
        'reorder_level',
        'expiry_date',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get inventory items for a specific branch
    public function getInventoryByBranch($branchId)
    {
        return $this->where('branch_id', $branchId)->findAll();
    }

    // Get low stock items (quantity <= reorder_level)
    public function getLowStockItems($branchId)
    {
        return $this->where('branch_id', $branchId)
                    ->where('quantity <= reorder_level')
                    ->findAll();
    }

    // Update quantity
    public function updateQuantity($inventoryId, $newQuantity)
    {
        return $this->update($inventoryId, ['quantity' => $newQuantity]);
    }

    // Add new inventory item
    public function addItem($data)
    {
        return $this->insert($data);
    }

    // Update existing item
    public function updateItem($inventoryId, $data)
    {
        return $this->update($inventoryId, $data);
    }

    // Delete item
    public function deleteItem($inventoryId)
    {
        return $this->delete($inventoryId);
    }

    // Get item by ID
    public function getItem($inventoryId)
    {
        return $this->find($inventoryId);
    }
}
