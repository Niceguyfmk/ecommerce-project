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

    public function getCategoryName($categoryFilter){
        $category = $this->where('category_id', $categoryFilter)->first();

        // Return the category name if found, otherwise return null
        return $category ? $category['category_name'] : null;
    }
}
