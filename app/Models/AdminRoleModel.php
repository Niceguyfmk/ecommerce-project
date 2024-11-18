<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminRoleModel extends Model
{
    protected $table            = 'admin_role';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['role'];

    public function getAllRoles(){
        return $this->findAll();
    }
}
