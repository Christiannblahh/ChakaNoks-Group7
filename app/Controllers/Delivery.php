<?php
namespace App\Controllers;
use App\Models\DeliveryModel;
use App\Models\PurchaseOrderModel;
use CodeIgniter\RESTful\ResourceController;

class Delivery extends ResourceController {
    protected $modelName = DeliveryModel::class;
    protected $format = 'json';

    /**
     * Get all deliveries
     * GET /delivery
     */
    public function index()
    {
        $model = new DeliveryModel();
        $deliveries = $model->select('d.*, po.status as order_status, s.supplier_name, u.email')
                            ->join('purchase_orders po', 'd.order_id = po.order_id', 'left')
                            ->join('suppliers s', 'po.supplier_id = s.supplier_id', 'left')
                            ->join('users u', 'd.logistics_id = u.user_id', 'left')
                            ->orderBy('d.scheduled_date', 'ASC')
                            ->findAll();
        return $this->respond($deliveries);
    }

    /**
     * Get specific delivery
     * GET /delivery/:id
     */
    public function show($id = null)
    {
        $model = new DeliveryModel();
        $delivery = $model->select('d.*, po.*, s.supplier_name, pr.branch_id, b.branch_name')
                          ->join('purchase_orders po', 'd.order_id = po.order_id', 'left')
                          ->join('suppliers s', 'po.supplier_id = s.supplier_id', 'left')
                          ->join('purchase_requests pr', 'po.request_id = pr.request_id', 'left')
                          ->join('branches b', 'pr.branch_id = b.branch_id', 'left')
                          ->where('d.delivery_id', $id)
                          ->first();
        
        if (!$delivery) {
            return $this->failNotFound('Delivery not found');
        }
        return $this->respond($delivery);
    }

    /**
     * Mark delivery as delivered and set exact timestamp
     * POST /delivery/mark-delivered/:id
     */
    public function markDelivered($id) {
        $model = new DeliveryModel();
        $now = date('Y-m-d H:i:s');
        $session = session();
        
        $updated = $model->update($id, [
            'status' => 'Delivered',
            'delivered_at' => $now
        ]);
        
        if ($updated) {
            // Update purchase order status
            $delivery = $model->find($id);
            $orderModel = new PurchaseOrderModel();
            $orderModel->updateStatus($delivery['order_id'], 'Delivered');
            
            // Log audit
            $this->logAudit('DELIVERY_MARKED_DELIVERED', "Delivery #$id marked as delivered", $session->get('user_id'));
            
            return $this->respond(['success' => true, 'delivered_at' => $now]);
        } else {
            return $this->fail('Failed to update delivery status');
        }
    }

    /**
     * Update delivery status
     * POST /delivery/:id/status
     */
    public function updateStatus($id)
    {
        $request = service('request');
        $session = session();

        $status = $request->getPost('status');
        if (!in_array($status, ['Scheduled', 'In Transit', 'Delivered'])) {
            return $this->fail('Invalid status');
        }

        $model = new DeliveryModel();
        $updateData = ['status' => $status];

        if ($status === 'Delivered') {
            $updateData['delivered_at'] = date('Y-m-d H:i:s');
            
            // Update purchase order status
            $delivery = $model->find($id);
            $orderModel = new PurchaseOrderModel();
            $orderModel->updateStatus($delivery['order_id'], 'Delivered');
        }

        $updated = $model->update($id, $updateData);

        if ($updated) {
            $this->logAudit('DELIVERY_STATUS_UPDATED', "Delivery #$id status updated to $status", $session->get('user_id'));
            return $this->respond(['success' => true, 'status' => $status]);
        } else {
            return $this->fail('Failed to update delivery status');
        }
    }

    /**
     * Schedule a delivery for a purchase order
     * POST /delivery/schedule
     */
    public function scheduleDelivery()
    {
        $request = service('request');
        $session = session();

        $orderId = $request->getPost('order_id');
        $scheduledDate = $request->getPost('scheduled_date');
        $logisticsId = $request->getPost('logistics_id');

        if (!$orderId || !$scheduledDate) {
            return $this->fail('Order ID and scheduled date are required');
        }

        $model = new DeliveryModel();
        
        // Check if delivery already exists
        $existing = $model->where('order_id', $orderId)->first();
        if ($existing) {
            return $this->fail('Delivery already scheduled for this order');
        }

        try {
            $deliveryId = $model->insert([
                'order_id' => $orderId,
                'logistics_id' => $logisticsId ?? $session->get('user_id'),
                'scheduled_date' => $scheduledDate,
                'status' => 'Scheduled'
            ]);

            $this->logAudit('DELIVERY_SCHEDULED', "Delivery scheduled for order #$orderId on $scheduledDate", $session->get('user_id'));
            return $this->respondCreated(['success' => true, 'delivery_id' => $deliveryId]);
        } catch (\Exception $e) {
            return $this->fail('Failed to schedule delivery: ' . $e->getMessage());
        }
    }

    /**
     * Get deliveries by status
     * GET /delivery/status/:status
     */
    public function getByStatus($status)
    {
        $model = new DeliveryModel();
        $deliveries = $model->where('status', $status)
                            ->orderBy('scheduled_date', 'ASC')
                            ->findAll();
        return $this->respond($deliveries);
    }

    /**
     * Get pending deliveries (scheduled or in transit)
     * GET /delivery/pending
     */
    public function getPending()
    {
        $model = new DeliveryModel();
        $deliveries = $model->whereIn('status', ['Scheduled', 'In Transit'])
                            ->orderBy('scheduled_date', 'ASC')
                            ->findAll();
        return $this->respond($deliveries);
    }

    /**
     * Get overdue deliveries
     * GET /delivery/overdue
     */
    public function getOverdue()
    {
        $model = new DeliveryModel();
        $today = date('Y-m-d');
        $overdue = $model->where('scheduled_date <', $today)
                         ->whereIn('status', ['Scheduled', 'In Transit'])
                         ->orderBy('scheduled_date', 'ASC')
                         ->findAll();
        return $this->respond($overdue);
    }

    /**
     * Get deliveries for a branch
     * GET /delivery/branch/:branch_id
     */
    public function getByBranch($branchId)
    {
        $db = \Config\Database::connect();
        $deliveries = $db->table('deliveries d')
                         ->select('d.*, po.*, b.branch_name, s.supplier_name')
                         ->join('purchase_orders po', 'd.order_id = po.order_id', 'left')
                         ->join('purchase_requests pr', 'po.request_id = pr.request_id', 'left')
                         ->join('branches b', 'pr.branch_id = b.branch_id', 'left')
                         ->join('suppliers s', 'po.supplier_id = s.supplier_id', 'left')
                         ->where('pr.branch_id', $branchId)
                         ->orderBy('d.scheduled_date', 'DESC')
                         ->get()
                         ->getResultArray();
        return $this->respond($deliveries);
    }

    /**
     * Get delivery statistics
     * GET /delivery/stats
     */
    public function getStats()
    {
        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        $stats = [
            'total_scheduled' => $db->table('deliveries')->where('status', 'Scheduled')->countAllResults(),
            'in_transit' => $db->table('deliveries')->where('status', 'In Transit')->countAllResults(),
            'delivered_today' => $db->table('deliveries')
                                   ->where('status', 'Delivered')
                                   ->where('DATE(delivered_at)', $today)
                                   ->countAllResults(),
            'overdue' => $db->table('deliveries')
                           ->where('scheduled_date <', $today)
                           ->whereIn('status', ['Scheduled', 'In Transit'])
                           ->countAllResults()
        ];

        return $this->respond($stats);
    }

    /**
     * Create a new shipment/delivery
     * POST /delivery/create
     */
    public function create()
    {
        $request = service('request');
        $session = session();

        // Get input
        $orderId = $request->getPost('order_id');
        $scheduledDate = $request->getPost('scheduled_date');
        $status = $request->getPost('status') ?? 'Scheduled';

        // Validation
        if (!$orderId) {
            return $this->fail('Purchase Order ID is required', 400);
        }

        if (!is_numeric($orderId)) {
            return $this->fail('Purchase Order ID must be a valid number', 400);
        }

        if (!$scheduledDate) {
            return $this->fail('Scheduled delivery date is required', 400);
        }

        // Validate status
        if (!in_array($status, ['Scheduled', 'In Transit'])) {
            return $this->fail('Status must be either "Scheduled" or "In Transit"', 400);
        }

        // Verify purchase order exists
        $orderModel = new PurchaseOrderModel();
        $order = $orderModel->find($orderId);
        if (!$order) {
            return $this->fail('Purchase Order #' . $orderId . ' not found', 404);
        }

        // Check if delivery already exists for this order
        $model = new DeliveryModel();
        $existing = $model->where('order_id', $orderId)->first();
        if ($existing) {
            return $this->fail('Delivery already exists for this purchase order', 400);
        }

        try {
            // Validate date format
            $dateObj = \DateTime::createFromFormat('Y-m-d H:i:s', $scheduledDate);
            if (!$dateObj || $dateObj->format('Y-m-d H:i:s') !== $scheduledDate) {
                return $this->fail('Invalid date format. Expected: YYYY-MM-DD HH:MM:SS', 400);
            }

            // Create delivery
            $deliveryId = $model->insert([
                'order_id' => $orderId,
                'logistics_id' => $session->get('user_id'),
                'scheduled_date' => $scheduledDate,
                'status' => $status
            ]);

            // Log audit
            $this->logAudit('DELIVERY_CREATED', "New shipment created for order #$orderId scheduled for $scheduledDate", $session->get('user_id'));

            return $this->respondCreated([
                'success' => true,
                'delivery_id' => $deliveryId,
                'message' => 'Shipment created successfully'
            ]);
        } catch (\Exception $e) {
            return $this->fail('Failed to create shipment: ' . $e->getMessage(), 500);
        }
    }

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
}
