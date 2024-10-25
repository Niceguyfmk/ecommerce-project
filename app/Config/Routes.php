<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group("users", ["namespace" => "App\Controllers\Users"], function($routes){
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
