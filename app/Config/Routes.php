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
    $routes->get('profile', 'Home::profile');    
    $routes->get('logout', 'Users\UserController::logout');
    $routes->post('update/(:num)', 'Users\UserController::updateUser/$1');
});

$routes->get('/shop', 'Home::shop');
$routes->get('/shop-detail/(:num)', 'Home::detail/$1');
$routes->get('/checkout', 'CartItemsController::checkout');

$routes->get('/cart', 'CartItemsController::viewCart');
/* $routes->get('items', 'TempCartController::getCartItems');
 */
$routes->group('cart', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->post('add/(:num)', 'CartItemsController::addItem/$1');       
    $routes->post('update/(:num)', 'CartItemsController::updateItem/$1'); 
    $routes->post('remove/(:num)', 'CartItemsController::removeItem/$1'); 
});

$routes->delete('clear', 'CartItemsController::clearCart');


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
    //Coupons
    $routes->get('coupons', 'ProductController::couponsView');
    $routes->post('addCoupons', 'ProductController::addCoupon');
    $routes->post('applyCoupon', 'ProductController::applyCoupon');
    $routes->post('couponID', 'ProductController::getCouponId');

});

/* $routes->group('temp-cart', function ($routes) {
    
    
}); */


//Orders
$routes->post('/order/create', 'OrdersController::create');

$routes->group("order", ["namespace" => "App\Controllers", 'filter' => 'jwt_auth'], function($routes){
    $routes->get('viewOrders', 'OrdersController::viewTable');
    $routes->get('orderDetails/(:num)', 'OrderItemsController::orderItemDetail/$1');
    $routes->post('orderUpdate/(:num)', 'OrdersController::orderStatusUpdate/$1');
    $routes->get('delete/(:num)', 'OrdersController::deleteOrder/$1');

    $routes->get('order-tracking', 'OrderTrackingController::index');
    $routes->post('order-tracking/updateTrackingStatus', 'OrderTrackingController::updateTrackingStatus');
    $routes->get('order-tracking/timeline/(:num)', 'OrderTrackingController::viewTimeline/$1');
    $routes->get('order-tracking/delete/(:num)', 'OrderTrackingController::deleteStatus/$1');
});

//$routes->get('stripe', 'StripeController::stripe');
$routes->get('success', 'CartItemsController::success');
$routes->get('cancel', 'CartItemsController::cancel');
$routes->post('payment', 'CartItemsController::payment');
$routes->post('stripe/webhook', 'CartItemsController::stripeWebhook');
$routes->post('payment', 'CartItemsController::handlePaymentIntentSucceeded');
$routes->post('payment', 'CartItemsController::handlePaymentIntentFailed');
