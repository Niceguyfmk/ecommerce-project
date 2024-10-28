<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function login(){

        return view('login');
    }

    
    public function adminDashboard(){

        return view('include/header') . view('include/sidebar') . view('include/nav') . view('index')
         . view('include/footer');
    }

    public function register(){

        return view('include/header') . view('include/sidebar') . view('include/nav') . view('register')
         . view('include/footer');
    }
}
