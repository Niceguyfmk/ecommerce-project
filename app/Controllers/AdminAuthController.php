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
            session()->set('message', 'Log in Success!');
            
            return redirect()->to('auth/admin')->with('userName', $userData['username']);
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
                "role_id" => $userData["role_id"]
            ]
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public function userProfile(){

        $userData = $this->request->userData;
        $pageTitle = 'Profile';
    
        return view('include/header', ['pageTitle' => $pageTitle]) 
        . view('include/sidebar') 
        . view(name: 'include/nav') 
        . view('profile', [
            "message" => "User Profile Information",
            "data" => $userData
            ])
        . view('include/footer');
    }

    public function updateAdminPass()
    {
        // Get data from the form
        $admin_id = $this->request->getPost('id');
        $password = $this->request->getPost('password');
        $confirm_password = $this->request->getPost('confirm_password');
        
        // Early return for missing fields
        if (empty($admin_id)) {
            return $this->respond(['status' => 'error', 'message' => 'Admin ID is required.']);
        }
        if (empty($password) || empty($confirm_password)) {
            return $this->respond(['status' => 'error', 'message' => 'All fields are required.']);
        }
        if ($password === $confirm_password) {
            return $this->respond(['status' => 'error', 'message' => 'Passwords do match.']);
        }
    
        // Fetch admin record and verify password
        $admin = $this->model->find($admin_id);
        if (!$admin || !password_verify($password, $admin['password'])) {
            return $this->respond(['status' => 'error', 'message' => 'Invalid original password or admin not found.']);
        }
    
        // Hash new password and update data
        $hashed_password = password_hash($confirm_password, PASSWORD_DEFAULT);
        $data = [
            'password' => $hashed_password
        ];
    
        if ($this->model->updateData($admin_id, $data)) {
            return $this->respond(['status' => 'success', 'message' => 'Admin data updated successfully.']);
        }
    
        return $this->respond(['status' => 'error', 'message' => 'Failed to update admin data.']);
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

            return redirect()->to('/loginPage')->with('success','logout successful');
        }else{
            
            return $this->respond([
                "status" => false,
                "message" => "failed to logged out",
            ]);
        }
    }
}
