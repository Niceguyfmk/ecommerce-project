<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminUserModel extends Model
{
    protected $table            = 'admin_users';
    protected $primaryKey       = 'admin_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "email",
        "password",
        "role_id"
    ];


    
    public function registerAdminUser($data){
        return $this->save($data);
    }

    public function getAdminUsers(){
        return $this->findAll();
    }

    public function getAdmin($admin_id){
        return $this->find($admin_id);
    }

    public function updateData($admin_id, $data)
    {

        return $this->update($admin_id, $data); 
    }

    public function deleteAdminUserById($user_id){
        return $this->delete($user_id);
    }
}

