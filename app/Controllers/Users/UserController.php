<?php

namespace App\Controllers\Users;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use Config\Google;
use Google\Client as GoogleClient;
use Firebase\JWT\JWT;
use App\Models\OrderItemsModel;
use App\Models\TokenBlacklisted;

class UserController extends ResourceController
{
    protected $modelName = UserModel::class;

    protected $format = "json"; 

    public function login(){
        $message = session()->getFlashdata('success');  
        $errorMessage = session()->getFlashdata('error');
        $pageTitle = 'Login';

        return  view('shop-Include/header', ['pageTitle' => $pageTitle])
          . view('shop/login', ['message' => $message, 'errorMessage' => $errorMessage,])
          . view('shop-Include/footer');
    }

    public function register(){
        $message = session()->getFlashdata('message');
        $pageTitle = 'Registration';

        return  view('shop-Include/header', ['pageTitle' => $pageTitle])
          . view('shop/register', ['message' => $message])
           .view('shop-Include/footer');
    }

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
                "rules" => "required|min_length[3]", 
                "errors" => array(
                    "required" => "Name is required",
                    "min_length" => "Name must be at least 3 characters"
                ),
            ),
            "password" => array(
                "rules" => "required|min_length[3]", 
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
            "name" => $this->request->getVar("name"),
            "email" => $this->request->getVar("email"), 
            "password" => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
            "address" => $this->request->getVar("address"),
        ];

        //Save  
        if($this->model->registerUser($UserData)){
            session()->setFlashdata('message', 'User Registered Successfully');
            return redirect()->to(base_url('/'))->with('success', 'User Registered Successfully');

        }else{

            return $this->respond([
                "status" => false,
                "message" => "Failed to register User"
            ]);
        }
    }

    public function userAuthenticate(){
        //Validation
        $validationRules = array(
            "email" => array(
                "rules" => "required|valid_email",
                "errors" => array(
                    "required" => "Email is required",
                    "valid_email" => "Please enter a valid email address",
                ),
            ),
            "password" => array(
                "rules" => "required|min_length[6]", 
                "errors" => array(
                    "required" => "Password is required",
                    "min_length" => "Password must be at least 6 characters"
                )
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
        
        if (!$userData) {
            // Log potential security event
            log_message('info', 'Login attempt with non-existent email: ' . $this->request->getVar("email"));
            
            return $this->respond([
                "status" => false,
                "message" => "Incorrect email or password"
            ]);
        }

        // Validate user and password
        //You can access the user's hashed password using $authorData['password'].
        if (password_verify($this->request->getVar("password"), $userData['password'])) {

            $token = $this->generateToken($userData);
            //Security: Regenerate session ID to prevent session fixation
            session()->regenerate();
            //store the token and data in session
            session()->set('jwtToken', $token);
            session()->set('userData', $userData);
            session()->set('userRole', 'user');
            session()->setFlashdata('message', 'Log in Success!');
            
            return redirect()->to(base_url('/'))->with('success', 'Logged in successfully.');
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
                "id" => $userData['user_id'],
                "email" => $userData["email"],
                "password" => $userData["password"],
            ]
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public function loginWithGoogle()
    {
        $google = new Google();
        $client = new GoogleClient();
        $client->setClientId($google->clientId);
        $client->setClientSecret($google->clientSecret);
        $client->setRedirectUri($google->redirectUri);
        $client->addScope("email");
        $client->addScope("profile");

        $authUrl = $client->createAuthUrl();
        return redirect()->to($authUrl);
    }

public function authGoogle()
{
    $google = new Google();
    $client = new GoogleClient();
    $client->setClientId($google->clientId);
    $client->setClientSecret($google->clientSecret);
    $client->setRedirectUri($google->redirectUri);
    $client->addScope("email");
    $client->addScope("profile");

    // Handle the OAuth 2.0 authentication code exchange
    $code = $this->request->getGet('code'); // Get the authorization code from the query params
    log_message('debug', 'Google auth code: ' . $code);

    if ($code) {
        // Exchange authorization code for access token
        $token = $client->fetchAccessTokenWithAuthCode($code);

        // Check if token is valid
        if (isset($token['access_token'])) {
            $client->setAccessToken($token['access_token']);

            // Fetch the user's profile data from Google
            $oauth2Service = new \Google_Service_Oauth2($client);
            $googleUser = $oauth2Service->userinfo->get(); // Get user data from Google

            log_message('debug', 'Google User Info: ' . print_r($googleUser, true));

            // Retrieve user email and name
            $email = $googleUser->email;
            $name = $googleUser->name;
            $address = null; // Assuming you won't have the address at this point
            $password = null; // No password for Google users

            // Check if user exists in the database
            $userModel = new UserModel();
            $existingUser = $userModel->userExists($email);

            log_message('debug', 'User Exists Check: ' . print_r($existingUser, true));

            if (!$existingUser) {
                // Add the new user to the database
                $inserted = $userModel->addUser($email, $name, $address, $password);

                // Log the result of the user creation
                log_message('debug', 'User Inserted: ' . $inserted);

                if ($inserted) {
                    // Successfully created the user
                    log_message('debug', 'New user created: ' . $email);
                } else {
                    // If user creation failed
                    log_message('error', 'Failed to create new user: ' . $email);
                }
            } else {
                // If the user already exists
                log_message('debug', 'User already exists: ' . $email);
            }

            // Redirect to the login page (you can modify this to a dashboard or another page)
            return redirect()->to(base_url('/user/login'))->with('success', 'Logged in successfully.');
        } else {
            // If token is not valid, show an error message
            log_message('error', 'Google Authentication Failed: Invalid token');
            return redirect()->to(base_url('/user/login'))->with('error', 'Failed to authenticate using Google.');
        }
    } else {
        // If no code is present in the request, show an error
        log_message('error', 'Google Authentication Failed: No code received');
        return redirect()->to(base_url('/user/login'))->with('error', 'Google authentication failed.');
    }
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

            return redirect()->to(base_url('/'))->with('success','logged out successfully');
        }else{
            
            return $this->respond([
                "status" => false,
                "message" => "failed to logged out",
            ]);
        }
    }

    //CRUD
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
        $user = $this->model->getUser($id);
    
        if ($user) {
            // Get post data
            $name = $this->request->getPost('name');
            $address = $this->request->getPost('address');
            $password = $this->request->getPost('password');
            $confirm_password = $this->request->getPost('confirm_password');
    
            // Verify original password
            if (!password_verify($password, $user['password'])) {
                return $this->respond(['status' => 'error', 'message' => 'Invalid original password']);
            }
    
            // Prepare data for update
            $data = [
                'name' => $name,
                'address' => $address
            ];
    
            // Update password only if a new one is provided
            if (!empty($confirm_password)) {
                // Hash new password
                $hashed_password = password_hash($confirm_password, PASSWORD_DEFAULT);
                $data['password'] = $hashed_password;
            }
    
            // Update user data
            $this->model->updateUser($id, $data);
            // Update the session data to reflect changes
            $session = session();
            $session->set('user_id', $id);
            $session->set('name', $name);
            $session->set('address', $address);
            // fetch the updated user data to return to the view
            $userData = [
                'name' => $session->get('name'),
                'address' => $session->get('address'),
                'user_id' => $session->get('user_id')
            ];
            
/*             $pageTitle = 'Organic Shop-Detail';
            $message = session()->getFlashdata('success');  
            $errorMessage = session()->getFlashdata('error');
            $pageTitle = 'Profile';
            
            $userData = session()->get('userData'); 
            
            return view('shop-include/header', ['pageTitle' => $pageTitle]) 
    
            . view('shop/profile', [
                "heading" => "User Profile",
                "pageTitle" => $pageTitle,
                "userData" => $userData,
                "errorMessage"=> $errorMessage,
                "message"=> $message
                ])
            . view('shop-include/footer'); */
            return redirect()->to('/');
        }
    }
    

    public function deleteUser($id)
    {
        // Logic to delete a user by ID
    }

    public function profileOrderDetail($orderId){
        $pageTitle = 'Organic Item Details';
        $message = session()->getFlashdata('success');  
        $errorMessage = session()->getFlashdata('error');
        
        $userData = session()->get('userData'); 
        $userID = $userData['user_id'];

        $ordersItemsModel = new OrderItemsModel();
        $orderItems = $ordersItemsModel->getOrderItemDetails($orderId);
        
        return view('shop-include/header', ['pageTitle' => $pageTitle]) 

        . view('shop/order-details', [
            "heading" => "User Profile",
            "pageTitle" => $pageTitle,
            "userData" => $userData,
            "orderItems" => $orderItems,
            "errorMessage"=> $errorMessage,
            "message"=> $message
            ])
        . view('shop-include/footer');
    }    
}