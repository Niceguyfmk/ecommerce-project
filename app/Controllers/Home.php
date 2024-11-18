<?php

namespace App\Controllers;
use Firebase\JWT\JWT;
class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function login(){
        
        return view('login');
    }
    
    public function adminDashboard() {

        $message = session()->getFlashdata('message');
        return view('include/header') 
            . view('include/sidebar') 
            . view('include/nav') 
            . view('index', ['message' => $message])
            . view('include/footer');
    }
   
    public function register(){

        return view('include/header') . view('include/sidebar') . view('include/nav') . view('register')
         . view('include/footer');
    }
}
