<?php

namespace App\Controllers;
use Firebase\JWT\JWT;
class Home extends BaseController
{
    public function index(): string
    {
        $message = session()->getFlashdata('message');
        $pageTitle = 'Dashboard';
    
        return  view('shop-Include/header', ['pageTitle' => $pageTitle])
          . view('shop/index', ['message' => $message])
           .view('shop-Include/footer');
            
    }

    public function login(){
        
        return view('login');
    }
    
    public function adminDashboard() {
        $message = session()->getFlashdata('message');
        $pageTitle = 'Dashboard';
    
        return view('include/header', ['pageTitle' => $pageTitle]) 
            . view('include/sidebar') 
            . view('include/nav') 
            . view('index', ['message' => $message]) 
            . view('include/footer');
    }   
   
    public function register(){
        $pageTitle = 'Add User';
        return view('include/header', ['pageTitle' => $pageTitle]) . view('include/sidebar') . view('include/nav') . view('register')
         . view('include/footer');
    }
}
