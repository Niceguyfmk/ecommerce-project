<?php

namespace App\Libraries;

use CodeIgniter\Database\Config;

class idGenerator
{
    public function generateId()
    {
        // Generate a random 6-digit number
        $id = str_pad(mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);

        return $id;
    }
}
