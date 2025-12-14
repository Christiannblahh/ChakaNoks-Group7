<?php
namespace App\Models;
use CodeIgniter\Model;
class StockRecordModel extends Model
{
    protected $table = 'stock_records';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'item_name', 'action', 'details', 'datetime'
    ];
    public $timestamps = false;
}
