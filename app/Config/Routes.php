<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Register User and Login User
$routes->post("/add", "Users\UserController::addUser");
$routes->post("/login", "AuthenticationController::login");
