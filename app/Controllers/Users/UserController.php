<?php

namespace App\Controllers\Users;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class UserController extends ResourceController
{
    protected $modelName = UserModel::class;

    protected $format = "json"; 

    public function addUser(){
                
        // Validation
        $validationRules = array(
            "email" => array(
                "rules" => "required|is_unique[users.email]",
                "errors" => array(
                    "required" => "Email is required",
                    "is_unique" => "Email is already in use"
                ),
            ),
            "name" => array(
                "rules" => "required|min_length[3]", // Corrected this line
                "errors" => array(
                    "required" => "Name is required",
                    "min_length" => "Name must be at least 3 characters"
                ),
            ),
            "password" => array(
                "rules" => "required|min_length[6]", // Corrected this line
                "errors" => array(
                    "required" => "Password is required",
                    "min_length" => "Password must be at least 6 characters long"
                ),
            ),
            "phone_no" => array(
                "rules" => "required|min_length[10]", // Corrected this line
                "errors" => array(
                    "required" => "Phone number is required",
                    "min_length" => "Phone number must be at least 10 digits"
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
            "name" => $this->request->getVar("name"),
            "email" => $this->request->getVar("email"), 
            "password" => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
            "phone_no" => $this->request->getVar("phone_no"),
            "role" => $this->request->getVar("role"),
        ];

        //Save Author 
        if($this->model->registerUser($UserData)){

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

    public function listAllUsers()
    {
       /*  $users = $this->model->getUsers();

        return $this->respond([
            "status" => true,
            "message" => "Successfully returned list of users",
            "users" => $users
        ]); */
    }

    public function getSingleUser($user_id)
    {
       /*  $user = $this->model->getProduct($user_id);
        
        if($user){
            return $this->respond([
                "status" => true,
                "message" => "Successfully found product",
                "user" => $user
            ]);
        }else{
            return $this->respond([
                "status" => false,
                "message" => "Failed to find user",
            ]);
        } */
    }

    public function updateUser($id)
    {
        // Logic to update a user by ID
    }

    public function deleteUser($id)
    {
        // Logic to delete a user by ID
    }
}