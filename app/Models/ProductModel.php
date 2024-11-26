<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'product_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;  
    protected $useTimestamps = true;

    protected $allowedFields    = [
        "name",
        "base_price",
        "description",
        "category_id",
        'long_description',
    ];

    protected $perPage = 9;
    
    public function getProducts(){
        return $this->findAll();
    }

    public function getProduct($product_id){
        return $this->find($product_id);
    }

    public function updateProductwithId($product_id, $updated_data){
        return $this->update($product_id, $updated_data);
    }

    public function deleteProductById($product_id){
        return $this->delete($product_id);
    }
    public function filterProducts($keyword = null, $categoryFilter = null)
    {
        // Step 1: Start building the query for the 'products' table
        $builder = $this->table('products');
        
        if ($keyword) {
            $builder->like('name', $keyword); // Filter by name
        }
        
        if ($categoryFilter) {
            $builder->where('category_id', $categoryFilter); // Filter by category
        }
        
        return $builder->paginate(9); // 9 items per page
    }

}
