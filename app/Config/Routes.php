<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/loginPage', 'Home::login');
//Login Admin
$routes->post("/adminLogin", "AdminAuthController::login");
//CRUD on Admin Routes
$routes->get("admin/(:num)", "Users\AdminUserController::getSingleAdmin/$1");


//Protected Auth API Admin Routes
$routes->group('auth', ['namespace' => 'App\Controllers', 'filter' => 'jwt_auth'], function($routes) {
    //Admin Dashboard View
    $routes->get('admin', 'Home::adminDashboard');
    //user registration page
    $routes->get('register', 'Home::register');
    //Adding a user using register function
    $routes->post("addAdmin", "Users\AdminUserController::addAdminUser");
    //user profile page
    $routes->get('profile', 'AdminAuthController::userProfile');    
    //Updating Password in Profile function
    $routes->post("updateAdminPassword", "AdminAuthController::updateAdminPass");
    //Deleting User function
    $routes->get("admin/(:num)", "Users\AdminUserController::deleteAdmin/$1");
    //Admin User table page
    $routes->get("adminList", "Users\AdminUserController::adminList");
    //User Role update function
    $routes->post("updateRole", "Users\AdminUserController::update_role");
    //Delete User
    $routes->get("admin/delete/(:num)", "Users\AdminUserController::deleteAdmin/$1");
    //user logout function
    $routes->get('logout', 'AdminAuthController::logout');
});


//CRUD on Protected Products Routes
$routes->group("product", ["namespace" => "App\Controllers\Products", 'filter' => 'jwt_auth'], function($routes){
    $routes->get('createProduct', 'ProductController::addProductView');
    $routes->post('addProduct', 'ProductController::addProduct');
    $routes->get('viewProducts', 'ProductController::listProductView');
    $routes->post('update/(:num)', 'ProductController::updateProduct/$1');
    $routes->get('delete/(:num)', 'ProductController::deleteProduct/$1');
    $routes->get('updateAttributes/(:num)', 'ProductController::updateAttributesView/$1');
    $routes->post('attributes/(:num)', 'ProductController::saveAttributes/$1');
});
