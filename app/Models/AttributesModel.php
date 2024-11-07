<?php

namespace App\Models;

use CodeIgniter\Model;

class AttributesModel extends Model
{
    protected $table            = 'attributes';
    protected $primaryKey       = 'attribute_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['attribute_name'];

    public function getAllAttributes(){

        return $this->findAll();
    }
}