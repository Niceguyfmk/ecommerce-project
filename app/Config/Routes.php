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
$routes->post("/add", "Users\UserController::addUser");
$routes->post("/login", "AuthenticationController::login");
$routes->post('auth/logout', 'AuthenticationController::logout');
