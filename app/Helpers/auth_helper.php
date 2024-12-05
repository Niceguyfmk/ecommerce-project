<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\AdminUserModel;
use App\Models\UserModel;

if (!function_exists('getUserDataFromToken')) {
    function getUserDataFromToken($token)
    {

        $key = getenv("JWT_KEY");
        
        $token = str_replace('Bearer ', '', $token); // Remove Bearer prefix

        try {
            // Decode the token
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $userData = (array) $decoded->user;

            // Determine the model to use based on 'role_id' or 'table' in the token
            if (isset($userData)) {

                if ($userData['table'] === 'admin') {
                    $model = new AdminUserModel();
                    $idField = 'admin_id';
                } else {
                    $model = new UserModel();
                    $idField = 'user_id';
                }
            }

            // Fetch data based on the correct ID field
            return $model->where($idField, $userData['id'])->first(); 

        } catch (\Exception $e) {
            return null; // Invalid or expired token
        }
    }
}

