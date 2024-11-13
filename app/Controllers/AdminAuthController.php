<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\AdminUserModel;
use Firebase\JWT\JWT;
use App\Models\TokenBlacklisted;

class AdminAuthController extends ResourceController
{
    protected $modelName = AdminUserModel::class;

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
            //store the token and data in session
            session()->set('jwtToken', $token);
            session()->set('userData', $userData);
            session()->setFlashdata('message', 'Log in Success!');

            return redirect()->to('auth/admin');
            /* return $this->respond([
                "status" => true,
                "message" => "logged in",
                "token" => $token
        ]); */
        }else{
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
                "id" => $userData['admin_id'],
                "email" => $userData["email"],
                "password" => $userData["password"],
                "role" => $userData["role"]
            ]
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public function userProfile(){

        $userData = $this->request->userData;

        return view('include/header') 
        . view('include/sidebar') 
        . view(name: 'include/nav') 
        . view('profile', [
            "message" => "User Profile Information",
            "data" => $userData
            ])
        . view('include/footer');
    }

    public function logout(){

        $token = $this->request -> jwtToken;
        if (!$token) {

            return $this->respond([
                "status" => false,
                "message" => "No token found, user may already be logged out."
            ]);
        }
        $tokenBlacklistedObject = new TokenBlacklisted();
        
        if($tokenBlacklistedObject ->insert(["token" => $token])){
            
            session()->remove('jwtToken'); 
            session()->destroy();

            return redirect()->to('/loginPage');
        }else{
            
            return $this->respond([
                "status" => false,
                "message" => "failed to logged out",
            ]);
        }
    }
}
