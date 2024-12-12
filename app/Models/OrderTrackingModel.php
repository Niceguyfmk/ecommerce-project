<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderTrackingModel extends Model
{
    protected $table            = 'order_tracking';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_tracking_id',
        'order_id',
        'order_tracking_status',
        'status_time',
        'created_at',
    ];

    public function statusChanges($orderId){

                $builder = $this->db->table('order_tracking');
                $builder -> select('order_tracking.*, orders.unique_order_id as unique_id, users.name, users.user_id');
                $builder -> join('orders', 'orders.order_id = order_tracking.order_id');
                $builder -> join('users', 'users.user_id = orders.user_id');
                $builder -> where('order_tracking.order_id', $orderId);
        return  $builder -> get()->getResultArray();
    }

    public function deleteStatusById($id){
        return $this->delete($id);
    }

    public function ordersTrackingList(){

        $builder = $this->db->table('order_tracking');
        $builder -> select('order_tracking.*');
            // Use a subquery to get the latest tracking entry for each order
            $builder->whereIn('id', function($subquery) {
                $subquery->select('MAX(id)')
                        ->from('order_tracking')
                        ->groupBy('order_id');
            });
        return  $builder -> get()->getResultArray();
    }

}
