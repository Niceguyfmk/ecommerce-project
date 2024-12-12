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
        'unique_order_id',
        'user_id',
        'coupon_id',
        'total_amount',
        'status',
        'created_at'
    ];

    public function createOrder($data){
        return $this->insert($data); 
    }

    public function getOrders(){
        $builder = $this->db->table('orders');

        $builder->select('orders.*, users.email');
        $builder->join('users', 'users.user_id = orders.user_id');
        return $builder->get()->getResultArray();
    }

    public function updateOrder($order_id, $data){
        return $this->update($order_id, $data);

    }
    public function updateStatus($order_id, $detailsOrderTracking, $paymentStatus){

        $data = ['status' => 'completed'];
        return $this->update($order_id, $data);
    }

    public function deleteOrder($order_id){
        return $this->delete($order_id);
    }
}
