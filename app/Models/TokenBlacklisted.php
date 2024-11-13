<?php

namespace App\Models;

use CodeIgniter\Model;

class TokenBlacklisted extends Model
{
    protected $table            = 'token_blacklisted';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "token"
    ];
}
