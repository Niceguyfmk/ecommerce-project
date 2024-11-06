<?php

namespace App\Controllers\Users;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\AdminUserModel;

class AdminUserController extends ResourceController
{
    protected $modelName = AdminUserModel::class;

    protected $format = "json"; 

    public function addAdminUser(){
                
        // Validation
        $validationRules = array(
            "email" => array(
                "rules" => "required|is_unique[users.email]",
                "errors" => array(
                    "required" => "Email is required",
                    "is_unique" => "Email is already in use"
                ),
            ),
            
            "password" => array(
                "rules" => "required|min_length[3]", // Corrected this line
                "errors" => array(
                    "required" => "Password is required",
                    "min_length" => "Password must be at least 3 characters long"
                ),
            ),
            
            "role" => array(
                "rules" => "required",
                "errors" => array(
                    "required" => "Please select a role"
                ),
            ),
        );


        //if validation fails, show the errors 
        if(!$this->validate($validationRules)){
            return $this->respond(array(
                "status" => false,
                "message" => "Form Submission failed",
                "errors" => $this->validator->getErrors()
            ));
        }

        //getPost()
        $UserData = [
            "email" => $this->request->getVar("email"), 
            "password" => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
            "role" => $this->request->getVar("role"),
        ];

        //Save Author 
        if($this->model->registerAdminUser($UserData)){

            return $this->respond([
                "status" => true,
                "message" => "User Registered Successfully"
            ]);
        }else{

            return $this->respond([
                "status" => false,
                "message" => "Failed to register User"
            ]);
        }
    }
}
