<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use Firebase\JWT\JWT;
//add JWT Blacklisted for logout
class AuthenticationController extends ResourceController
{
    protected $modelName = UserModel::class;

    protected $format = "json"; 
    public function login(){
        //Validation
        $validationRules = array(
            "email" => array(
                "rules" => "required",
                "errors" => array(
                    "required" => "Email is required",
                ),
            ),
            "password" => array(
                "rules" => "required",
                "errors" => array(
                    "required" => "Password is required",
                ),
            )
        );

        if(!$this->validate($validationRules)){

            return $this->respond([
                "status"=> false,
                "message" => "All fields are required",
                "errors" => $this->validator->getErrors()
            ]);
        }    
        
        //The first() method retrieves the first matching record
        $userData = $this->model->where("email", $this->request->getVar("email"))->first();
    
        // Validate user and password
        //You can access the user's hashed password using $authorData['password'].
        if ($userData && password_verify($this->request->getVar("password"), $userData['password'])) {
            $token = $this->generateToken($userData);
            return $this->respond([
                "status" => true,
                "message" => "User logged in",
                'token' => $token]
            );
        } else {
            return $this->respond([
                "status" => false,
                "message" => "Incorrect email or password"
            ]);
        }

    }

    // Token generation
    private function generateToken($userData)
    {
        $key = getenv("JWT_KEY");

        $payload = [
            "iss" => "localhost",
            "aud" => "localhost",
            "iat" => time(),
            "exp" => time() + 3600, //expires in one hour 60 min * 60 sec = 3600sec 
            "user" => [
                "id" => $userData['id'],
                "role" => $userData["role"]
            ]
        ];

        return JWT::encode($payload, $key, 'HS256');
    }
}
