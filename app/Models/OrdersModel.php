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

    public function getOrders($order_id){
        $builder = $this->db->table('orders');

        $builder->select('orders.*, users.email');
        $builder->join('users', 'users.user_id = orders.user_id');
        $builder->where('orders.order_id', $order_id);
        return $builder->get()->getResultArray();
    }
}
