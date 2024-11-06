<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/loginPage', 'Home::login');
$routes->get('/admin', 'Home::adminDashboard');
$routes->get('/register', 'Home::register');


//Register User and Login User
$routes->post("/login", "AuthenticationController::login");

//CRUD on Users Routes
$routes->post("/addAdmin", "Users\AdminUserController::addAdminUser");
$routes->get("/list-all-users", "Users\UserController::listAllUsers");
$routes->get("user/(:num)", "Users\UserController::getSingleUser/$1");


//Protected API Routes
$routes->group("auth", ["namespace" => "App\Controllers", "filter" => "jwt_auth"], function($routes){
    
    $routes->get('logout', 'AuthenticationController::logout');
});

//CRUD on Products Routes
$routes->group("product", ["namespace" => "App\Controllers\Products"], function($routes){
    //POST - add product
    $routes->post("addProduct", "ProductController::addProduct");
    //GET - list all products
    $routes->get("list", "ProductController::listAllProducts");
    //GET -  get product by id
    $routes->get("(:num)", "ProductController::getSingleProduct/$1");
    //PUT - update product
    $routes->put("(:num)", "ProductController::updateProduct/$1");
    //DELETE - delete product
    $routes->delete("(:num)", "ProductController::deleteProduct/$1");
});
