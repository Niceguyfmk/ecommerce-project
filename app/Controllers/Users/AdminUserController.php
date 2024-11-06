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
            session()->setFlashdata('message', 'User Registered Successfully');
            return redirect()->to('/admin');
        }else{

            return $this->respond([
                "status" => false,
                "message" => "Failed to register User"
            ]);
        }
    }

    public function adminUsers(){
        
        $adminUsers = $this->model->getAdminUsers();

        return $this->respond([
            "status" => true,
            "message" => "Successfully returned list of users",
            "Users" => $adminUsers
        ]);
    }

    public function getSingleAdmin($admin_id){
        
        $admin = $this->model->getAdmin($admin_id);
        
        return $this->respond([
            "status" => true,
            "message" => "Successfully returned record of user",
            "User Data" => $admin
        ]);
    }

    public function updateAdmin($admin_id){
        
        $admin =  $this->model->getAdmin($admin_id);

        if($admin){

                    // Get the incoming JSON data
            $raw_data = file_get_contents("php://input");
            $updated_data = json_decode($raw_data, true);
            $admin_role = isset($updated_data["role"]) ? $updated_data["role"] : $admin["role"]; 
            /* $admin_password = isset($updated_data["password"]) ? updated_data["password"] : $admin["password"]; */
            
            if($this->model->update($admin_id, [
                "role" => $admin_role,

            ])){
                return $this->respond([
                    "status" => true,
                    "message" => "Successfully updated user data",
                ]);

            }else{
                return $this->respond([
                    "status" => false,
                    "message" => "Failed to update user data",
                ]);
            }

        }else{
            return $this->respond([
                "status" => false,
                "message" => "Failed to find user",
            ]);
        }
    }

    public function deleteAdmin($admin_id){
        $admin = $this->model->deleteAdminUserById($admin_id);
        
        return $this->respond([
            "status" => true,
            "message" => "Successfully deleted record of user",
        ]);
    }
}