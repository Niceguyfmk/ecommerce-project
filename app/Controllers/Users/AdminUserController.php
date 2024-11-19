<?php

namespace App\Controllers\Users;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\AdminUserModel;
use App\Models\AdminRoleModel;

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
            "role_id" => $this->request->getVar("role_id"),
        ];

        //Save Author 
        if($this->model->registerAdminUser($UserData)){
            session()->setFlashdata('message', 'User Registered Successfully');
            return redirect()->to('auth/admin');
        }else{

            return $this->respond([
                "status" => false,
                "message" => "Failed to register User"
            ]);
        }
    }

    public function adminList(){
        $users = $this->model->getAdminUsers();
        
        $roleModel = new AdminRoleModel();
        $roles = $roleModel->getAllRoles();  
        $message = session()->getFlashdata('message');

        $pageTitle = 'Admin Table';
    
        return view('include/header', ['pageTitle' => $pageTitle])  . view('include/sidebar') . view('include/nav') . view('adminList', [
            'users' => $users, 
            'roles' => $roles,
            'message' => $message
        ]) . view('include/footer');
    }
    
    public function update_role()
    {   

        $admin_id = $this->request->getPost('user_id');
        $role_id = $this->request->getPost('role_id'); 
        
        // Validate the inputs 
        if (empty($admin_id) || empty($role_id)) {
            return redirect()->back()->with('error', 'Invalid input data');
        }

        $data = ['role_id' => $role_id];
        if ($this->model->update($admin_id, $data)) {
            // Role updated successfully, redirect back with success message
            session()->setFlashdata('message', 'Role updated successfully');
            return redirect()->to('auth/adminList');
        } else {
            // Failed to update the role
            return redirect()->back()->with('error', 'Failed to update role');
        }
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

            /*

            This reads the raw POST data sent to the server.
            When you send data (like JSON) through an HTTP POST request,
             it’s stored in the body of the request. php://input provides access to this raw data.

            The json_decode() function converts the JSON string (from the POST data) into a PHP associative array.

            The second parameter, true, tells json_decode to return an associative array instead of an object.
            If it were false or omitted, it would return a PHP object.
            
            */

            $raw_data = file_get_contents("php://input");
            $updated_data = json_decode($raw_data, true);
            $admin_role = isset($updated_data["role"]) ? $updated_data["role"] : $admin["role"]; 
            
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
        $this->model->deleteAdminUserById($admin_id);
        
        return redirect()->to('/auth/adminList');

    }
}
