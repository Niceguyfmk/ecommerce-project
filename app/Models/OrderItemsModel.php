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
        $builder->select('DISTINCT order_items.*, orders.*, products.name, images.image_url, (CASE WHEN product_ratings.rating IS NOT NULL THEN 1 ELSE 0 END) as has_rated', false);

        // Join `orders` table with `order_items`
        $builder->join('orders', 'orders.order_id = order_items.order_id');
        
        // Join `products` table with `order_items` via `product_id`
        $builder->join('products', 'products.product_id = order_items.product_id');
        
        // Join `images` table with `products` via `product_id`
        $builder->join('images', 'images.product_id = products.product_id');

        // Join `product ratings` table with `products` via `product_id`

        $builder->join('product_ratings', 'product_ratings.product_id = products.product_id AND product_ratings.user_id = orders.user_id', 'left');

        // Apply the condition to filter by `order_id`
        $builder->where('order_items.order_id', $orderId);

        // Return the result as an array
        return $builder->get()->getResultArray();
    }

    public function getBestSellingProducts($limit = 6)
    {
        return $this->select('product_id, SUM(quantity) as total_quantity')
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'DESC')
            ->limit($limit)
            ->find();
    }

    public function getRevenueByCategory()
    {
        $builder = $this->db->table('order_items');
        $builder->select('product_categories.category_name, SUM(order_items.quantity * order_items.price) AS total_revenue');
        $builder->join('products', 'order_items.product_id = products.product_id');
        $builder->join('product_categories', 'products.category_id = product_categories.category_id');
        $builder->groupBy('product_categories.category_name');
        
        return $builder->get()->getResultArray();
    }


}
