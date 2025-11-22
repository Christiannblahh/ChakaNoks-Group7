<?php
namespace App\Controllers;
use App\Models\DeliveryModel;
use CodeIgniter\RESTful\ResourceController;
class Delivery extends ResourceController {
    protected $modelName = DeliveryModel::class;
    protected $format = 'json';

    // Mark delivery as delivered and set exact timestamp
    public function markDelivered($id) {
        $model = new DeliveryModel();
        $now = date('Y-m-d H:i:s');
        $updated = $model->update($id, [
            'status' => 'Delivered',
            'delivered_at' => $now
        ]);
        if ($updated) {
            return $this->respond(['success' => true, 'delivered_at' => $now]);
        } else {
            return $this->fail('Failed to update delivery status');
        }
    }
}
