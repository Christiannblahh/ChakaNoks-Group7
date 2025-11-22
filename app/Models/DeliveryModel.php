<?php
namespace App\Models;
use CodeIgniter\Model;
class DeliveryModel extends Model {
    protected $table = 'deliveries';
    protected $primaryKey = 'delivery_id';
    protected $allowedFields = [
        'order_id', 'logistics_id', 'scheduled_date', 'delivered_at', 'status'
    ];
}
