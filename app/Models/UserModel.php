<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "user_id",
        "name",
        "email",
        "password",
        "address",
        "created_at"
    ];

    public function registerUser($data){
        return $this->save($data);
    }

    public function addUser(string $email, string $name): bool
    {
        return $this->insert([
            'email' => $email,
            'name' => $name,
        ]);
    }

    public function getUser($id){
        return $this->where('user_id', $id)->first();
    }

    public function getAllUsers(){
        return $this->findAll();
    }

    public function userExists(string $email): bool
    {
        return $this->where('email', $email)->countAllResults() > 0;
    }   

    public function updateUser($user_id, $data){
        return $this->update($user_id,$data);
    }

    public function deleteUserById($user_id){
        return $this->delete($user_id);
    }

}
