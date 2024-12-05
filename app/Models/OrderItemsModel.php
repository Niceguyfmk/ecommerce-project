<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemsModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'order_items_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'product_attribute_id',
        'quantity',
        'price'
    ];

    public function getItem($order_id){
        $builder = $this->db->table('order_items');

        $builder->select('order_items.*, orders.*, products.name, users.email');
        $builder->join('orders', 'orders.order_id = order_items.order_id');
        $builder->join('users', 'users.user_id = orders.user_id');
        $builder->join('product_attributes', 'product_attributes.product_attribute_id = order_items.product_attribute_id');
        $builder->join('products', 'products.product_id = product_attributes.product_id');
        $builder->where('order_items.order_id', $order_id);
        return $builder->get()->getResultArray();
    }
}
