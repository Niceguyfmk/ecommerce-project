<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductMetaModel extends Model
{
    protected $table            = 'product_meta_values';
    protected $primaryKey       = 'meta_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'meta_key',
        'meta_value'
    ];

    public function addValues($data){
        return $this->insert( $data);
    }

    public function allValues(){
        return $this->findAll();
    }

    public function getValuebyID($id){
        return $this->find($id);
    }

    public function getValuebyProductID($product_id){
        return $this->where('product_id',$product_id)->get()->getResultArray();
    }

    public function deletebyID($id){
        return $this->delete($id);
    }
}

    
