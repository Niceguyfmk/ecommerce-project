<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/loginPage', 'Home::login');


//Product Views



//Register and Login Admin
$routes->post("/adminLogin", "AdminAuthController::login");

//CRUD on Admin Routes
$routes->post("/addAdmin", "Users\AdminUserController::addAdminUser");
$routes->get("admin/(:num)", "Users\AdminUserController::getSingleAdmin/$1");
$routes->put("admin/(:num)", "Users\AdminUserController::updateAdmin/$1");
$routes->delete("admin/(:num)", "Users\AdminUserController::deleteAdmin/$1");


//Protected Auth API Routes
$routes->group('auth', ['namespace' => 'App\Controllers', 'filter' => 'jwt_auth'], function($routes) {
    $routes->get('admin', 'Home::adminDashboard');
    $routes->get('register', 'Home::register');
    $routes->get("adminList", "Users\AdminUserController::adminUsers");

    $routes->get('profile', 'AdminAuthController::userProfile');    
    $routes->get('logout', 'AdminAuthController::logout');
});


//CRUD on Protected Products Routes
$routes->group("product", ["namespace" => "App\Controllers\Products", 'filter' => 'jwt_auth'], function($routes){
    $routes->get('createProduct', 'ProductController::addProductView');
    $routes->get('viewProducts', 'ProductController::listProductView');
    $routes->get('edit/(:num)', 'ProductController::updateProductView/$1');
    $routes->post('update/(:num)', 'ProductController::updateProduct/$1');
    $routes->get('delete/(:num)', 'ProductController::deleteProduct/$1');;
});
