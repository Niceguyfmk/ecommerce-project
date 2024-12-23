<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductRatingModel extends Model
{
    protected $table            = 'product_ratings';
    protected $primaryKey       = 'rating_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "rating_id",
        "product_id",
        "user_id",
        "rating",
        "created_at",
        "comment"
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    // Optional: Add validation rules
    protected $validationRules = [
        'product_id' => 'required|integer',
        'user_id'    => 'required|integer',
        'rating'     => 'required|integer|greater_than[0]|less_than_equal_to[5]',
    ];

    public function getRatings($product_id, $user_id)
    {
        return $this->where([
            'product_id' => $product_id,
            'user_id' => $user_id
        ])->first(); 
    }

    public function getallProductRatings($product_id){
        $builder = $this->db->table('product_ratings');
        $builder->select('product_ratings.*, users.name AS user_name');
        $builder->join('users', 'users.user_id = product_ratings.user_id');
        $builder->where('product_ratings.product_id', $product_id);
        return $builder->get()->getResultArray(); 
    }

    public function getAverageRating($product_id){
        $result = $this->builder()
        ->selectAvg('rating') 
        ->where('product_id', $product_id)
        ->get()
        ->getRowArray();
        return $result['rating'] ?? 0; 
    }

    //for the shop page, or for anything else where id isnt fed directly
    public function getProductAvgRating()
    {
        $builder = $this->db->table($this->table);
        $builder->select('product_id, AVG(rating) as avg_rating');
        $builder->groupBy('product_id');
        $result = $builder->get()->getResultArray();
        return $result;
    }

    //ALl Ratings for admin table
    public function getAllRatings(){
        return $this->select('product_ratings.*, products.name as product_name')
                ->join('products', 'products.product_id = product_ratings.product_id')
                ->findAll();
    }
    
}
