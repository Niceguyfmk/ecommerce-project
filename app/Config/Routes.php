<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/loginPage', 'Home::login');
$routes->get('/admin', 'Home::adminDashboard');
$routes->get('/register', 'Home::register');

//Product Views
$routes->get('/createProduct', 'Products\ProductController::addProductView');


//Register and Login Admin
$routes->post("/adminLogin", "AdminAuthController::login");

//CRUD on Admin Routes
$routes->post("/addAdmin", "Users\AdminUserController::addAdminUser");
$routes->get("/adminList", "Users\AdminUserController::adminUsers");
$routes->get("admin/(:num)", "Users\AdminUserController::getSingleAdmin/$1");
$routes->put("admin/(:num)", "Users\AdminUserController::updateAdmin/$1");
$routes->delete("admin/(:num)", "Users\AdminUserController::deleteAdmin/$1");


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
