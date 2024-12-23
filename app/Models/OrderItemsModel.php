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
        'product_id',
        'product_attribute_id',
        'quantity',
        'price'
    ];

    public function getItem($order_id){
        $builder = $this->db->table('order_items');

        $builder->select('order_items.*, orders.*, products.name, users.email');
        $builder->join('orders', 'orders.order_id = order_items.order_id');
        $builder->join('users', 'users.user_id = orders.user_id');
        $builder->join('products', 'products.product_id = order_items.product_id');
        $builder->where('order_items.order_id', $order_id);
        return $builder->get()->getResultArray();
    }

    //for shop profile -> order item details
    public function getOrderItemDetails($orderId){
    $builder = $this->db->table('order_items');

    // Select the columns needed from each table
    $builder->select('order_items.*, orders.*, products.name, images.image_url');

    // Join `orders` table with `order_items`
    $builder->join('orders', 'orders.order_id = order_items.order_id');
    
    // Join `products` table with `order_items` via `product_id`
    $builder->join('products', 'products.product_id = order_items.product_id');
    
    // Join `images` table with `products` via `product_id`
    $builder->join('images', 'images.product_id = products.product_id');

    // Apply the condition to filter by `order_id`
    $builder->where('order_items.order_id', $orderId);

    // Return the result as an array
    return $builder->get()->getResultArray();
}

}
