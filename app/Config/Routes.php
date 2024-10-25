<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Register User and Login User
$routes->post("/add", "Users\UserController::addUser");
$routes->post("/login", "AuthenticationController::login");



/* $routes->group("users", ["namespace" => "App\Controllers\Users"], function($routes){
    //POST - add product
    $routes->post("add", "UserController::addUser");
    //GET - list all products
    $routes->get("list", "UserController::listAllUsers");
    //GET -  get product by id
    $routes->get("(:num)", "UserController::getSingleUser/$1");
    //PUT - update product
    $routes->put("(:num)", "UserController::updateUser/$1");
    //DELETE - delete product
    $routes->delete("(:num)", "UserController::deleteUser/$1");
});

//need to add filter to the group
$routes->group('api', function($routes) {
    $routes->post('login', 'AuthenticationController::login');
    $routes->get('products', 'ProductController::index', ['filter' => 'auth']); 
}); */