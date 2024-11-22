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
        $this->insert( $data);
    }
}
