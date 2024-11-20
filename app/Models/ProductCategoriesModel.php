<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductCategoriesModel extends Model
{
    protected $table            = 'product_categories';
    protected $primaryKey       = 'category_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "category_name"
    ];

    public function getAllCategories(){

        return $this->findAll();
    }
}
