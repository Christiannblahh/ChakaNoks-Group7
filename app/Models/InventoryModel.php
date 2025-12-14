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

    // Get items that have reached or passed their expiry date
    public function getExpiredItems($branchId)
    {
        $currentDate = date('Y-m-d');

        return $this->where('branch_id', $branchId)
                    ->where('expiry_date IS NOT NULL', null, false)
                    ->where('DATE(expiry_date) <=', $currentDate)
                    ->findAll();
    }

    // Get items that will expire in the next 30 days (but not already expired)
    public function getNearExpiryItems($branchId)
    {
        $currentDate = date('Y-m-d');
        $next30 = date('Y-m-d', strtotime('+30 days'));

        return $this->where('branch_id', $branchId)
                    ->where('expiry_date IS NOT NULL', null, false)
                    ->where('DATE(expiry_date) >', $currentDate)
                    ->where('DATE(expiry_date) <=', $next30)
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
