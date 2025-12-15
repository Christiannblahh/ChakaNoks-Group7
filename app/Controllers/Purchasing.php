<?php

namespace App\Controllers;

use App\Models\PurchaseRequestModel;
use App\Models\PurchaseRequestItemModel;
use App\Models\PurchaseOrderModel;
use App\Models\OrderItemModel;
use App\Models\SupplierModel;
use CodeIgniter\RESTful\ResourceController;

class Purchasing extends ResourceController
{
    protected $format = 'json';

    // ===== PURCHASE REQUEST ENDPOINTS =====

    /**
     * Create a new purchase request from branch
     * POST /purchasing/requests/create
     */
    public function createRequest()
    {
        $request = service('request');
        $session = session();

        // Check role
        if (!in_array($session->get('role'), ['Branch Manager', 'Inventory Staff'])) {
            return $this->failForbidden('You do not have permission to create requests');
        }

        $branchId = $session->get('branch_id');
        $userId = $session->get('user_id');
        $items = $request->getPost('items') ?? [];

        // Validate required fields
        if (empty($items)) {
            return $this->failValidationError('At least one item is required');
        }

        // Validate each item
        $validationErrors = [];
        foreach ($items as $index => $item) {
            if (empty($item['item_name'])) {
                $validationErrors[] = "Item #" . ($index + 1) . ": Item name is required";
            }
            if (!isset($item['quantity']) || $item['quantity'] <= 0) {
                $validationErrors[] = "Item #" . ($index + 1) . ": Quantity must be greater than 0";
            }
            if (!isset($item['estimated_cost']) || $item['estimated_cost'] < 0) {
                $validationErrors[] = "Item #" . ($index + 1) . ": Estimated cost must be a positive number";
            }
        }

        if (!empty($validationErrors)) {
            return $this->failValidationError(implode('; ', $validationErrors));
        }

        $model = new PurchaseRequestModel();
        $itemModel = new PurchaseRequestItemModel();

        try {
            $db = \Config\Database::connect();
            $db->transStart();

            // Create request
            $requestData = [
                'branch_id' => $branchId,
                'requested_by' => $userId,
                'request_date' => date('Y-m-d H:i:s'),
                'status' => 'Pending',
                'notes' => $request->getPost('notes') ?? ''
            ];

            $requestId = $model->insert($requestData);

            // Add items
            foreach ($items as $item) {
                $itemModel->addItem([
                    'request_id' => $requestId,
                    'item_name' => $item['item_name'] ?? '',
                    'description' => $item['description'] ?? '',
                    'quantity_requested' => (int)($item['quantity'] ?? 0),
                    'unit' => $item['unit'] ?? 'pcs',
                    'estimated_cost' => (float)($item['estimated_cost'] ?? 0),
                    'notes' => $item['notes'] ?? ''
                ]);
            }

            // Log audit
            $this->logAudit('PURCHASE_REQUEST_CREATED', "Request #$requestId created", $userId);

            $db->transComplete();

            return $this->respondCreated([
                'success' => true,
                'request_id' => $requestId,
                'message' => 'Purchase request created successfully'
            ]);
        } catch (\Exception $e) {
            return $this->fail('Failed to create request: ' . $e->getMessage());
        }
    }

    /**
     * Get all requests for a branch
     * GET /purchasing/requests/branch/:branch_id
     */
    public function getRequestsByBranch($branchId)
    {
        $model = new PurchaseRequestModel();
        $requests = $model->getByBranch($branchId);
        return $this->respond($requests);
    }

    /**
     * Get pending requests (for approval)
     * GET /purchasing/requests/pending
     */
    public function getPendingRequests()
    {
        $session = session();
        
        // Only Central Admin can see pending
        if ($session->get('role') !== 'Central Admin' && $session->get('role') !== 'System Admin') {
            return $this->failForbidden('Access denied');
        }

        $model = new PurchaseRequestModel();
        $requests = $model->getPendingRequests();
        return $this->respond($requests);
    }

    /**
     * Get request details with items
     * GET /purchasing/requests/:id
     */
    public function getRequest($id)
    {
        $model = new PurchaseRequestModel();
        $itemModel = new PurchaseRequestItemModel();

        $request = $model->getRequestWithItems($id);
        if (!$request) {
            return $this->failNotFound('Request not found');
        }

        $request['items'] = $itemModel->getByRequest($id);
        return $this->respond($request);
    }

    /**
     * Approve a purchase request
     * POST /purchasing/requests/:id/approve
     */
    public function approveRequest($id)
    {
        $request = service('request');
        $session = session();
        
        // Only Central Admin can approve
        if ($session->get('role') !== 'Central Admin' && $session->get('role') !== 'System Admin') {
            return $this->failForbidden('Only Central Admin can approve requests');
        }

        $supplierId = $request->getPost('supplier_id');
        if (!$supplierId) {
            return $this->fail('Supplier is required');
        }

        $model = new PurchaseRequestModel();
        $purchaseModel = new PurchaseOrderModel();
        $itemModel = new PurchaseRequestItemModel();

        try {
            $db = \Config\Database::connect();
            $db->transStart();

            // Get request
            $requestData = $model->find($id);
            if (!$requestData) {
                return $this->failNotFound('Request not found');
            }

            // Update request status
            $model->updateStatus($id, 'Approved', $session->get('user_id'));

            // Create purchase order
            $orderData = [
                'request_id' => $id,
                'supplier_id' => $supplierId,
                'approved_by' => $session->get('user_id'),
                'order_date' => date('Y-m-d H:i:s'),
                'status' => 'Pending',
                'expected_delivery' => $request->getPost('expected_delivery') ?? date('Y-m-d', strtotime('+7 days')),
                'notes' => $request->getPost('notes') ?? ''
            ];

            $orderId = $purchaseModel->insert($orderData);

            // Add order items from request items
            $items = $itemModel->getByRequest($id);
            $orderItemModel = new OrderItemModel();
            $totalAmount = 0;

            foreach ($items as $item) {
                $itemTotal = $item['quantity_requested'] * $item['estimated_cost'];
                $totalAmount += $itemTotal;

                $orderItemModel->addItem([
                    'order_id' => $orderId,
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity_requested'],
                    'unit_price' => $item['estimated_cost'],
                    'total_price' => $itemTotal
                ]);
            }

            // Update order total
            $purchaseModel->update($orderId, ['total_amount' => $totalAmount]);

            // Log audit
            $this->logAudit('PURCHASE_REQUEST_APPROVED', "Request #$id approved, Order #$orderId created", $session->get('user_id'));

            $db->transComplete();

            return $this->respond([
                'success' => true, 
                'message' => 'Request approved and order created',
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            return $this->fail('Failed to approve request: ' . $e->getMessage());
        }
    }

    /**
     * Deny a purchase request
     * POST /purchasing/requests/:id/deny
     */
    public function denyRequest($id)
    {
        $request = service('request');
        $session = session();
        
        if ($session->get('role') !== 'Central Admin' && $session->get('role') !== 'System Admin') {
            return $this->failForbidden('Only Central Admin can deny requests');
        }

        $model = new PurchaseRequestModel();
        $notes = $request->getPost('reason') ?? 'No reason provided';

        $updated = $model->update($id, [
            'status' => 'Denied',
            'notes' => $notes,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($updated) {
            $this->logAudit('PURCHASE_REQUEST_DENIED', "Request #$id denied: $notes", $session->get('user_id'));
            return $this->respond(['success' => true, 'message' => 'Request denied']);
        } else {
            return $this->fail('Failed to deny request');
        }
    }

    // ===== PURCHASE ORDER ENDPOINTS =====

    /**
     * Get all purchase orders
     * GET /purchasing/orders
     */
    public function getOrders()
    {
        $model = new PurchaseOrderModel();
        $orders = $model->select('po.*, s.supplier_name, b.branch_name')
                        ->join('suppliers s', 'po.supplier_id = s.supplier_id', 'left')
                        ->join('purchase_requests pr', 'po.request_id = pr.request_id', 'left')
                        ->join('branches b', 'pr.branch_id = b.branch_id', 'left')
                        ->orderBy('po.order_date', 'DESC')
                        ->findAll();
        return $this->respond($orders);
    }

    /**
     * Get pending orders
     * GET /purchasing/orders/pending
     */
    public function getPendingOrders()
    {
        $model = new PurchaseOrderModel();
        $orders = $model->select('po.*, s.supplier_name')
                        ->join('suppliers s', 'po.supplier_id = s.supplier_id', 'left')
                        ->where('po.status', 'Pending')
                        ->orderBy('po.order_date', 'ASC')
                        ->findAll();
        return $this->respond($orders);
    }

    /**
     * Get order details
     * GET /purchasing/orders/:id
     */
    public function getOrder($id)
    {
        $model = new PurchaseOrderModel();
        $itemModel = new OrderItemModel();

        $order = $model->getWithDetails($id);
        if (!$order) {
            return $this->failNotFound('Order not found');
        }

        $order['items'] = $itemModel->getByOrder($id);
        return $this->respond($order);
    }

    /**
     * Update order status
     * POST /purchasing/orders/:id/status
     */
    public function updateOrderStatus($id)
    {
        $request = service('request');
        $session = session();

        $status = $request->getPost('status');
        if (!in_array($status, ['Pending', 'Shipped', 'Delivered', 'Cancelled'])) {
            return $this->fail('Invalid status');
        }

        $model = new PurchaseOrderModel();
        $updated = $model->updateStatus($id, $status);

        if ($updated) {
            $this->logAudit('PURCHASE_ORDER_STATUS_UPDATED', "Order #$id status updated to $status", $session->get('user_id'));
            return $this->respond(['success' => true, 'status' => $status]);
        } else {
            return $this->fail('Failed to update order');
        }
    }

    /**
     * Get orders by supplier
     * GET /purchasing/suppliers/:supplier_id/orders
     */
    public function getSupplierOrders($supplierId)
    {
        $model = new PurchaseOrderModel();
        $orders = $model->where('supplier_id', $supplierId)
                        ->orderBy('order_date', 'DESC')
                        ->findAll();
        return $this->respond($orders);
    }

    // ===== SUPPLIER ENDPOINTS =====

    /**
     * Get all suppliers
     * GET /purchasing/suppliers
     */
    public function getSuppliers()
    {
        $model = new SupplierModel();
        $suppliers = $model->getActive();
        return $this->respond($suppliers);
    }

    /**
     * Get supplier details
     * GET /purchasing/suppliers/:id
     */
    public function getSupplier($id)
    {
        $model = new SupplierModel();
        $supplier = $model->getWithStats($id);
        if (!$supplier) {
            return $this->failNotFound('Supplier not found');
        }
        return $this->respond($supplier);
    }

    /**
     * Create supplier (Admin only)
     * POST /purchasing/suppliers/create
     */
    public function createSupplier()
    {
        $request = service('request');
        $session = session();

        if ($session->get('role') !== 'System Admin' && $session->get('role') !== 'Central Admin') {
            return $this->failForbidden('Only admins can create suppliers');
        }

        $model = new SupplierModel();
        $data = [
            'supplier_name' => $request->getPost('supplier_name'),
            'contact_person' => $request->getPost('contact_person'),
            'email' => $request->getPost('email'),
            'phone' => $request->getPost('phone'),
            'address' => $request->getPost('address'),
            'city' => $request->getPost('city'),
            'state' => $request->getPost('state'),
            'postal_code' => $request->getPost('postal_code'),
            'country' => $request->getPost('country'),
            'supplier_type' => $request->getPost('supplier_type'),
            'status' => 'Active',
            'rating' => 5
        ];

        if (empty($data['supplier_name']) || empty($data['email'])) {
            return $this->fail('Supplier name and email are required');
        }

        try {
            $id = $model->insert($data);
            $this->logAudit('SUPPLIER_CREATED', "Supplier #$id created", $session->get('user_id'));
            return $this->respondCreated(['success' => true, 'supplier_id' => $id]);
        } catch (\Exception $e) {
            return $this->fail('Failed to create supplier: ' . $e->getMessage());
        }
    }

    /**
     * Update supplier
     * POST /purchasing/suppliers/:id/update
     */
    public function updateSupplier($id)
    {
        $request = service('request');
        $session = session();

        if ($session->get('role') !== 'System Admin' && $session->get('role') !== 'Central Admin') {
            return $this->failForbidden('Only admins can update suppliers');
        }

        $model = new SupplierModel();
        $data = [
            'supplier_name' => $request->getPost('supplier_name'),
            'contact_person' => $request->getPost('contact_person'),
            'email' => $request->getPost('email'),
            'phone' => $request->getPost('phone'),
            'address' => $request->getPost('address'),
            'city' => $request->getPost('city'),
            'state' => $request->getPost('state'),
            'postal_code' => $request->getPost('postal_code'),
            'country' => $request->getPost('country'),
            'supplier_type' => $request->getPost('supplier_type')
        ];

        try {
            $model->update($id, $data);
            $this->logAudit('SUPPLIER_UPDATED', "Supplier #$id updated", $session->get('user_id'));
            return $this->respond(['success' => true]);
        } catch (\Exception $e) {
            return $this->fail('Failed to update supplier: ' . $e->getMessage());
        }
    }

    /**
     * Get supplier performance stats
     * GET /purchasing/suppliers/:id/stats
     */
    public function getSupplierStats($id)
    {
        $db = \Config\Database::connect();
        $stats = $db->table('purchase_orders po')
                    ->select('COUNT(po.order_id) as total_orders, 
                             SUM(CASE WHEN po.status = "Delivered" THEN 1 ELSE 0 END) as delivered_count,
                             AVG(CASE WHEN po.status = "Delivered" THEN 1 ELSE 0 END) as delivery_rate,
                             AVG(DATEDIFF(d.delivered_at, po.expected_delivery)) as avg_days_late')
                    ->join('deliveries d', 'po.order_id = d.order_id', 'left')
                    ->where('po.supplier_id', $id)
                    ->get()
                    ->getRowArray();

        return $this->respond($stats ?? []);
    }

    // ===== UTILITY FUNCTIONS =====

    /**
     * Log audit trail
     */
    private function logAudit($action, $description, $userId)
    {
        try {
            $db = \Config\Database::connect();
            $db->table('audit_logs')->insert([
                'action' => $action,
                'description' => $description,
                'user_id' => $userId,
                'timestamp' => date('Y-m-d H:i:s'),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
            ]);
        } catch (\Exception $e) {
            // Silently fail if audit log fails
        }
    }

    /**
     * Get dashboard statistics
     * GET /purchasing/stats
     */
    public function getStats()
    {
        $db = \Config\Database::connect();

        $stats = [
            'pending_requests' => $db->table('purchase_requests')->where('status', 'Pending')->countAllResults(),
            'pending_orders' => $db->table('purchase_orders')->where('status', 'Pending')->countAllResults(),
            'overdue_deliveries' => $db->table('purchase_orders')
                                        ->where('expected_delivery <', date('Y-m-d'))
                                        ->where('status !=', 'Delivered')
                                        ->countAllResults(),
            'total_suppliers' => $db->table('suppliers')->where('status', 'Active')->countAllResults()
        ];

        return $this->respond($stats);
    }
}
