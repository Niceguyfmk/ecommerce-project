<?php

namespace App\Controllers\Users;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use Config\Google;
use Google\Client as GoogleClient;
use Firebase\JWT\JWT;
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
                "rules" => "required|min_length[3]", // Corrected this line
                "errors" => array(
                    "required" => "Name is required",
                    "min_length" => "Name must be at least 3 characters"
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
            "name" => $this->request->getVar("name"),
            "email" => $this->request->getVar("email"), 
            "password" => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
            "address" => $this->request->getVar("address"),
        ];

        //Save Author 
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

                // If the user doesn't exist in the database, register them
                $userModel = new UserModel();
                if (!$userModel->userExists($email)) {
                    // Add the new user to the database
                    $userModel->addUser($email, $name);
                }
                
                // Redirect to the homepage with a success message
                return redirect()->to(base_url('/user/login'))->with('success', 'Logged in successfully.');
            } else {
                // If token is not valid, show an error message
                
                return redirect()->to(base_url('/user/login'))->with('error', 'Failed to authenticate using Google.');
            }
        } else {
            // If no code is present in the request, show an error
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
        // Logic to update a user by ID
    }

    public function deleteUser($id)
    {
        // Logic to delete a user by ID
    }

    
}