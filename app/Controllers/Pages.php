<?php

namespace App\Controllers;

class Pages extends BaseController
{
	public function index()
	{
		return redirect()->to(site_url('/'));
	}

	public function users()
	{
        return view('pages/users');
	}

	public function backups()
	{
        return view('pages/backups');
	}

	public function settings()
	{
        return view('pages/settings');
	}

	public function shipments()
	{
		return view('pages/shipments');
	}

	public function routes()
	{
		return view('pages/routes');
	}

	public function suppliers()
	{
		return view('pages/suppliers');
	}

	public function notifications()
	{
		return view('pages/notifications');
	}

	public function messages()
	{
		return view('pages/messages');
	}

	public function inventory()
	{
		return view('pages/inventory');
	}

	// Purchase approvals view
	public function purchaseApprovals()
	{
		$session = session();
		
		// Check if user is logged in
		if (!$session->get('logged_in')) {
			return redirect()->to(site_url('login'));
		}
		
		// Allow Central Admin, System Admin, and Inventory Staff to view approvals
		$allowedRoles = ['Central Admin', 'System Admin', 'Inventory Staff'];
		if (!in_array($session->get('role'), $allowedRoles)) {
			return redirect()->to(site_url('login'))->with('error', 'You do not have permission to access this page.');
		}
		
		return view('pages/purchase_approvals');
	}

	// Purchase orders view
	public function purchaseOrders()
	{
		return view('pages/purchase_orders');
	}

	// Get inventory data for AJAX
	public function getInventory()
	{
		$inventoryModel = new \App\Models\InventoryModel();
		$branchId = session()->get('branch_id') ?? 1; // Default to branch 1 for demo

		$inventory = $inventoryModel->getInventoryByBranch($branchId);

		return $this->response->setJSON($inventory);
	}

	// Add new inventory item
	public function addInventoryItem()
	{
		$request = service('request');
		$inventoryModel = new \App\Models\InventoryModel();

		$data = [
			'branch_id' => session()->get('branch_id') ?? 1,
			'item_name' => $request->getPost('item_name'),
			'item_description' => $request->getPost('item_description'),
			'unit' => $request->getPost('unit'),
			'quantity' => (int) $request->getPost('quantity'),
			'reorder_level' => (int) $request->getPost('reorder_level'),
			'expiry_date' => $request->getPost('expiry_date') ?: null,
		];

		if ($inventoryModel->addItem($data)) {
			// Log to stock_records
			$stockRecordModel = new \App\Models\StockRecordModel();
			$details = 'Quantity: ' . $data['quantity'] . ', Unit: ' . $data['unit'] . ', Reorder Level: ' . $data['reorder_level'];
if ($data['expiry_date']) $details .= ', Expiry Date: ' . $data['expiry_date'];
$stockRecordModel->insert([
				'item_name' => $data['item_name'],
				'action' => 'Added',
				'details' => $details,
				'datetime' => date('Y-m-d H:i:s'),
			]);
			return $this->response->setJSON(['success' => true, 'message' => 'Item added successfully']);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Failed to add item']);
		}
	}

	// Update inventory item
	public function updateInventoryItem()
	{
		$request = service('request');
		$inventoryModel = new \App\Models\InventoryModel();

		$inventoryId = $request->getPost('inventory_id');
		// Fetch old item BEFORE update
		$old = $inventoryModel->getItem($inventoryId);
		$data = [
			'item_name' => $request->getPost('item_name'),
			'item_description' => $request->getPost('item_description'),
			'unit' => $request->getPost('unit'),
			'quantity' => (int) $request->getPost('quantity'),
			'reorder_level' => (int) $request->getPost('reorder_level'),
			'expiry_date' => $request->getPost('expiry_date') ?: null,
		];

		if ($inventoryModel->updateItem($inventoryId, $data)) {
			// Log to stock_records
			$stockRecordModel = new \App\Models\StockRecordModel();
			$old = $inventoryModel->getItem($inventoryId);
$changes = [];
foreach ([
    'quantity', 'unit', 'reorder_level', 'expiry_date', 'item_description'
] as $field) {
    $oldVal = isset($old[$field]) ? $old[$field] : '';
    $newVal = isset($data[$field]) ? $data[$field] : '';
    if ($oldVal != $newVal) {
        $changes[] = ucfirst(str_replace('_', ' ', $field)) . ": $oldVal → $newVal";
    }
}
if (count($changes) === 1 && strpos($changes[0], 'Quantity:') === 0) {
    // Only quantity changed
    $details = 'Quantity set to: ' . $data['quantity'];
} else if ($changes) {
    $details = implode(', ', $changes);
} else {
    $details = '';
}
$stockRecordModel->insert([
				'item_name' => $data['item_name'],
				'action' => '',
				'details' => $details,
				'datetime' => date('Y-m-d H:i:s'),
			]);
			return $this->response->setJSON(['success' => true, 'message' => 'Item updated successfully']);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Failed to update item']);
		}
	}

	// Delete inventory item
	public function deleteInventoryItem()
	{
		$request = service('request');
		$inventoryModel = new \App\Models\InventoryModel();

		$inventoryId = $request->getPost('inventory_id');

		// Get item name for logging before deletion
			$item = $inventoryModel->getItem($inventoryId);
			if ($inventoryModel->deleteItem($inventoryId)) {
				// Log to stock_records
				$stockRecordModel = new \App\Models\StockRecordModel();
				$details = '';
if ($item) {
	$details = 'Quantity: ' . $item['quantity'] . ', Unit: ' . $item['unit'] . ', Reorder Level: ' . $item['reorder_level'];
	if ($item['expiry_date']) $details .= ', Expiry Date: ' . $item['expiry_date'];
	if ($item['item_description']) $details .= ', Description: ' . $item['item_description'];
}
$stockRecordModel->insert([
					'item_name' => $item ? $item['item_name'] : 'Unknown',
					'action' => 'Deleted',
					'details' => $details ? $details : 'Item removed from inventory',
					'datetime' => date('Y-m-d H:i:s'),
				]);
				return $this->response->setJSON(['success' => true, 'message' => 'Item deleted successfully']);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete item']);
		}
	}

	// Get stock alerts (low stock + expired items)
	public function getLowStockAlerts()
	{
		$inventoryModel = new \App\Models\InventoryModel();
		$branchId = session()->get('branch_id') ?? 1;

		$lowStockItems = $inventoryModel->getLowStockItems($branchId);
		$expiredItems = $inventoryModel->getExpiredItems($branchId);
		$nearExpiryItems = $inventoryModel->getNearExpiryItems($branchId);

		$alerts = [];

		foreach ($lowStockItems as $item) {
			$alerts[] = [
				'type' => 'low',
				'item_name' => $item['item_name'] ?? '',
				'quantity' => $item['quantity'] ?? 0,
				'reorder_level' => $item['reorder_level'] ?? 0,
				'expiry_date' => $item['expiry_date'] ?? null,
			];
		}

		foreach ($nearExpiryItems as $item) {
			$alerts[] = [
				'type' => 'near_expiry',
				'item_name' => $item['item_name'] ?? '',
				'quantity' => $item['quantity'] ?? 0,
				'reorder_level' => $item['reorder_level'] ?? 0,
				'expiry_date' => $item['expiry_date'] ?? null,
			];
		}

		foreach ($expiredItems as $item) {
			$alerts[] = [
				'type' => 'expired',
				'item_name' => $item['item_name'] ?? '',
				'quantity' => $item['quantity'] ?? 0,
				'reorder_level' => $item['reorder_level'] ?? 0,
				'expiry_date' => $item['expiry_date'] ?? null,
			];
		}

		return $this->response->setJSON($alerts);
	}

	public function reports()
	{
		return view('pages/reports');
	}

    // Branch Manager pages
    public function branchRequests()
    {
        return view('branch/requests');
    }

    public function branchTransfers()
    {
        return view('branch/transfers');
    }

    public function branchSettings()
    {
        return view('branch/settings');
    }

    // Actions
    public function createUser()
    {
        $request = service('request');
        $session = session();

        $name = trim((string) $request->getPost('name'));
        $email = trim((string) $request->getPost('email'));
        $role = trim((string) $request->getPost('role'));

        if ($name === '' || $email === '' || $role === '') {
            return redirect()->back()->with('error', 'All fields are required.');
        }

        // Placeholder: integrate with database later
        $session->setFlashdata('success', 'User created (simulation): ' . esc($name) . ' — ' . esc($role));
        return redirect()->to(site_url('pages/users'));
    }

    public function initiateBackup()
    {
        $session = session();
        // Placeholder for actual backup logic
        $session->setFlashdata('success', 'Backup started (simulation).');
        return redirect()->to(site_url('pages/backups'));
    }

    public function restoreBackup()
    {
        $request = service('request');
        $session = session();

        $backupId = trim((string) $request->getPost('backup_id'));
        if ($backupId === '') {
            return redirect()->back()->with('error', 'Select a backup to restore.');
        }

        // Placeholder for restore logic
        $session->setFlashdata('success', 'Restore queued for backup #' . esc($backupId) . ' (simulation).');
        return redirect()->to(site_url('pages/backups'));
    }

    public function updateSettings()
    {
        $request = service('request');
        $session = session();

        $appName = trim((string) $request->getPost('app_name'));
        $timezone = trim((string) $request->getPost('timezone'));

        if ($appName === '' || $timezone === '') {
            return redirect()->back()->with('error', 'All fields are required.');
        }

        // Placeholder: persist to config or DB later
        $session->setFlashdata('success', 'Settings updated (simulation): ' . esc($appName) . ' — ' . esc($timezone));
        return redirect()->to(site_url('pages/settings'));
    }

    // Branch Manager actions (simulated)
    public function branchCreateRequest()
    {
        $request = service('request');
        $session = session();
        $item = trim((string) $request->getPost('item'));
        $quantity = (int) $request->getPost('quantity');
        if ($item === '' || $quantity <= 0) {
            return redirect()->back()->with('error', 'Item and quantity are required.');
        }
        $session->setFlashdata('success', 'Purchase request submitted (simulation).');
        return redirect()->to(site_url('branch/requests'));
    }

    public function branchCreateTransfer()
    {
        $request = service('request');
        $session = session();
        $from = trim((string) $request->getPost('from_branch'));
        $to = trim((string) $request->getPost('to_branch'));
        $item = trim((string) $request->getPost('item'));
        $quantity = (int) $request->getPost('quantity');
        if ($from === '' || $to === '' || $item === '' || $quantity <= 0) {
            return redirect()->back()->with('error', 'All fields are required.');
        }
        $session->setFlashdata('success', 'Transfer request sent (simulation).');
        return redirect()->to(site_url('branch/transfers'));
    }

	public function stockRecords()
	{
		return view('pages/stock_records');
	}

	public function logout()
	{
		$session = session();
		$session->destroy();
		return redirect()->to(site_url('login'));
	}
}
