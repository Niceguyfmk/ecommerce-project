<?php

namespace App\Models;

use CodeIgniter\Model;

class OrdersModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'order_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'user_id',
        'coupon_id',
        'total_amount',
        'status',
        'created_at'
    ];

    public function createOrder($data){
        return $this->insert($data); 
    }
}
