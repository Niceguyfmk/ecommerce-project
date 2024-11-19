<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductAttributesModel extends Model
{
    protected $table            = 'product_attributes';
    protected $primaryKey       = 'product_attribute_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "product_id",
        "attribute_id",
        "unit_type",
        "unit_quantity",
        "price",
        "discount_price",
        "stock",
        "is_default"
    ];

    public function addAttributes($data){
        return $this->insert($data);
    }
}
