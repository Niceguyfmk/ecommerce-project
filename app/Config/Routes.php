<?php

use CodeIgniter\Router\RouteCollection;

//Shop Website
$routes->get('/', 'Home::index');

$routes->group('user', function ($routes) {
    $routes->get('register', 'Users\UserController::register');
    $routes->post('addUser', 'Users\UserController::addUser');
    $routes->get('login', 'Users\UserController::login');
    $routes->post('userAuthenticate', 'Users\UserController::userAuthenticate'); 
    $routes->get('loginWithGoogle', 'Users\UserController::loginWithGoogle');
    $routes->get('authGoogle', 'Users\UserController::authGoogle');
});

$routes->group('user', ['namespace' => 'App\Controllers', 'filter' => 'jwt_auth'], function($routes) {
    $routes->get('logout', 'Users\UserController::logout');
});




$routes->get('/shop', 'Home::shop');
$routes->get('/shop-detail/(:num)', 'Home::detail/$1');
$routes->get('/checkout', 'Home::checkout');

$routes->get('/cart', 'TempCartController::viewCart');
$routes->get('items', 'TempCartController::getCartItems');

$routes->group('cart', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->post('add/(:num)', 'TempCartController::addItem/$1');       
    $routes->post('update/(:num)', 'TempCartController::updateItem/$1'); 
    $routes->post('remove/(:num)', 'TempCartController::removeItem/$1'); 
});

$routes->delete('clear', 'TempCartController::clearCart');


//Dashboard Links
$routes->get('/loginPage', 'Home::login');
//Login Admin
$routes->post("/adminLogin", "AdminAuthController::login");

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
    //Attributes Routes
    $routes->get('updateAttributes/(:num)', 'ProductController::updateAttributesView/$1');
    $routes->post('attributes/(:num)', 'ProductController::saveAttributes/$1');
    $routes->delete('deleteAttribute/(:num)', 'ProductController::deleteAttribute/$1');
    //Meta Values Routes
    $routes->get('updateMetaTable/(:num)', 'ProductController::updateMetaTableView/$1');
    $routes->post('metaValues/(:num)', 'ProductController::saveMetaValues/$1');
    $routes->delete('deleteMeta/(:num)', 'ProductController::deleteMeta/$1');

});

/* $routes->group('temp-cart', function ($routes) {
    
    
}); */

