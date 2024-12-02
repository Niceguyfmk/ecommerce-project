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

}
