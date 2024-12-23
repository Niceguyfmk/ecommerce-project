<?php

namespace App\Controllers;
use App\Models\OrdersModel;
use App\Models\ProductModel;
use App\Models\TempCartModel;
use App\Models\CartItemsModel;
use App\Models\CartModel;
use App\Models\TransactionsModel;
use CodeIgniter\RESTful\ResourceController;

use Stripe;
class CartItemsController extends ResourceController
{
    public function __construct()
    {
        $this->model = new CartItemsModel(); 
    }
    public function viewCart()
    {
        // Set page title
        $pageTitle = "Cart";
    
        // Retrieve UID from the cookie or return an error
        $uid = $this->request->getCookie('uid');
        if (!$uid) {
            return $this->response->setStatusCode(400, 'No UID cookie found');
        }
    
        // Check if user is logged in
        $userData = session()->get('userData');
        $cartItems = [];
    
        if ($userData && isset($userData['user_id'])) {
            // Logged-in user: Use CartItemsModel
            $userId = $userData['user_id'];
    
            $cartModel = new CartModel();
            $cart = $cartModel->where('user_id', $userId)->first();
    
            if ($cart) {
                $cartId = $cart['cart_id'];
                $cartItemsModel = new CartItemsModel();
                $cartItems = $cartItemsModel->getUserCart($cartId);
            }
        } else {
            // Guest user: Use TempCartModel
            $tempCartModel = new TempCartModel();
            $cartItems = $tempCartModel->getTempCartItems($uid);
        }
    
        // Render views
        return view('shop-Include/header', ['pageTitle' => $pageTitle]) 
            . view('shop/cart', ['cartItems' => $cartItems]) 
            . view('shop-Include/footer');
    }
    
    //checkout page
    public function checkout()
    {
        $message = session()->getFlashdata('message');
        $pageTitle = 'Checkout';
    
        // Check if user is logged in
        $userData = session()->get('userData');
        if (!$userData || !isset($userData['user_id'])) {
            log_message('error', 'User is not logged in or userData is missing.');
            
            return redirect()->to('user/login')->with('error', 'You must be logged in to checkout.');
        }
    
        $userId = $userData['user_id']; 
    
        // Get UID from the cookie
        $uid = $this->request->getCookie('uid');
        if (!$uid) {
            return $this->response->setStatusCode(400, 'No UID cookie found');
        }

        // Fetch or create a permanent cart for the logged-in user
        $cartModel = new CartModel();
        $cart = $cartModel->where('user_id', $userId)->first();
    
        if (!$cart) {
            $cartId = $cartModel->insert([
                'user_id' => $userId,
                'coupon_id' => null,
            ]);
    
            $cart = $cartModel->find($cartId); // Retrieve the created cart
        }
    
        $cartId = $cart['cart_id'];
    
        // Transfer temporary cart items to permanent cart
        $tempCartModel = new TempCartModel();
        //for displaying on page
        $tempCartItems = $tempCartModel->getTempCartItems($uid);

        if (!empty($tempCartItems)) {
            log_message('error', 'No temporary cart items found for UID: ' . print_r($tempCartItems, true));
            $cartItemsModel = new CartItemsModel();
            foreach ($tempCartItems as $item) {
                if($item['status'] === '0'){
                    $data = [
                        'cart_id' => $cartId,
                        'product_id' => $item['product_id'],
                        'product_attribute_id' => $item['product_attribute_id'],
                        'quantity' => $item['quantity'],
                        'uid' => $uid,
                        'price' => $item['price']
                    ];
                    //log_message('notice', message: 'Adding item to cart: ' . print_r($data, true));
                    // Add item to the permanent cart, ignore duplicates
                    $cartItemsModel->addCartItem($data);
                }
            }

            // Update the temporary cart products status after transferring using uid
            $result = $tempCartModel->upadateStatusUsingUID($uid);
            if (!$result) {
                log_message('error', 'Failed to update the status of temporary cart items for UID: ' . $uid);
            }
        }
    
        // Retrieve all items from the permanent cart
        $cartItemsModel = new CartItemsModel();
        $cartItems = $cartItemsModel->getUserCart($cartId);
    
        // Load the checkout page with cart items
        return view('shop-Include/header', ['pageTitle' => $pageTitle])
            . view('shop/checkout', ['cartItems' => $cartItems, 'message' => $message])
            . view('shop-Include/footer');
    }
    public function addItem($productId)
    {
        //Ajax request or not
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400, 'Bad Request');
        }

        // Get the UID from the cookie
        $uid = $this->request->getCookie('uid');
        if (!$uid) {
            return $this->respond(['error' => 'UID cookie not found'], 400);
        }

        //Get POST VALUES
        $quantity = $this->request->getPost('quantity');
        $price = $this->request->getPost('price');

        // Validate inputs
        if (!$quantity || !$price) {
            return $this->respond(['error' => 'Invalid input'], 400);
        }

        // Validate quantity to be a positive integer
        if (!is_numeric($quantity) || $quantity <= 0) {
            return $this->respond(['error' => 'Invalid quantity value'], 400);
        }

        try {
            // Check if user is logged in
            $userData = session()->get('userData');
            $cartModel = new CartModel();
            $cartId = null;
            
            if($userData && isset($userData['user_id'])){
                $cartItemsModel = new CartItemsModel();
                $userId = $userData['user_id'];
                $cart = $cartModel->where('user_id', $userId)->first();

                if (!$cart) {
                    // Create a new cart if none exists
                    $cartId = $cartModel->insert([
                        'user_id' => $userId,
                        'coupon_id' => null,  // Default value
                    ], true); // Get the inserted ID
                } else {
                    $cartId = $cart['cart_id'];                    
                }

                 // Insert product into cart_items table with the correct cart_id
                $cartItemsModel->addCartItem([
                    'cart_id' => $cartId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'uid' => $uid,
                    'product_attribute_id' => 6, // Default or dynamic value
                ]);

                return $this->respond(['message' => 'Product added to cart successfully'], 200);

            }else{
                //if not logged in use tempCart to save items
                $cartItemsModel = new TempCartModel();

                // Add the item to the cart (TempCart model)
                $cartItemsModel->addItemsToTempCart([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'uid' => $uid, // Use UID instead of session_id
                    'product_attribute_id' => 6
                ]);

                // Respond with the updated cart count
                return $this->respond(['message' => 'Product added to cart successfully'], 200);
            }

        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->respond(['error' => 'Internal Server Error'], 500);
        }
    }

    public function updateItem($productId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400, 'Bad Request');
        }
        //get Quantity & Price by Post
        $quantity = $this->request->getPost('quantity');
        $quantity = (int) $quantity;
        $price = $this->request->getPost('price');

        // Validate inputs
        if (!$quantity || !$price) {
            return $this->respond(['error' => 'Invalid input'], 400);
        }   

        // Validate quantity to be a positive integer
        if (!is_numeric($quantity) || $quantity <= 0) {
            return $this->respond(['error' => 'Invalid quantity value'], 400);
        }

        // Get the UID from the cookie
        $uid = $this->request->getCookie('uid');
        if (!$uid) {
            return $this->respond(['error' => 'UID cookie not found'], 400);
        }

        try {
            // Check if user is logged in
            $userData = session()->get('userData');
            $cartModel = new CartModel();
            //model to use: if logged in or not
            $updateModel = "";
            
            if($userData && isset($userData['user_id'])){
                $updateModel = new CartItemsModel();
                $userId = $userData['user_id'];
                //$cart = $cartModel->where('user_id', $userId)->first();

                // Update the cart item in the model
                $updateStatus = $updateModel->updateCartItem($productId, $quantity);

                if ($updateStatus) {
                    // Respond with success if the update was successful
                    return $this->response->setJSON(['status' => 'success', 'message' => 'Cart item updated successfully']);
                } else {
                    // Respond with error if the update failed
                    return $this->response->setStatusCode(500, 'Error updating cart item');
                }

            }else{
                //if not logged in use tempCart to save items
                $updateModel = new TempCartModel();
                //check status of uid
                $status = $updateModel->getCartStatus($productId, $uid);
                //return $this->response->setStatusCode(404)->setJSON(['cart status' => $status]);

                //echo('status: ' . $status);
                if($status === '0'){
                    // Update the cart item in the model if status is 0
                    //echo(" id: " . $productId. " quantity: " . $quantity);
                    $updateStatus = $updateModel->updateCartItem($productId, $uid, $quantity);
                    if ($updateStatus) {
                        // Respond with success if the update was successful
                        return $this->response->setJSON(['status' => 'success', 'message' => 'Cart item updated successfully']);
                    } else {
                        // Respond with error if the update failed
                        return $this->response->setStatusCode(500, 'Error updating cart item');
                    }
                } else{
                    //echo("status: 1");
                    
                    //if status is 1, use tempCart to insert new row
                    $cartItemsModel = new TempCartModel();
        
                    // Add the item to the cart (TempCart model)
                    $cartItemsModel->addItemsToTempCart([
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'price' => $price,
                        'uid' => $uid, // Use UID instead of session_id
                        'product_attribute_id' => 6
                    ]);
        
                    // Respond with the updated cart count
                    return $this->respond(['message' => 'Product added to cart successfully'], 200);
                }

            }

        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->respond(['error' => 'Internal Server Error'], 500);
        }
    }

    public function removeItem($productId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400, 'Bad Request');
        }

        // Get the UID from the cookie
        $uid = $this->request->getCookie('uid');
        if (!$uid) {
            return $this->respond(['error' => 'UID cookie not found'], 400);
        }

        try {
            // Check if user is logged in
            $userData = session()->get('userData');
            $cartModel = new CartModel();
            //model to use: if logged in or not
            $deleteModel = "";
            
            if($userData && isset($userData['user_id'])){
                $deleteModel = new CartItemsModel();
                $userId = $userData['user_id'];

                $remove = $deleteModel->removeCartItem($productId, $uid);

                if ($remove) {
                    return $this->response->setJSON(['status' => 'success', 'message' => 'Item removed from cart']);
                } else {
                    return $this->respond([
                        'status'=> 'error',
                        'message'=> 'Failed to remove item'
                    ]);
                }

            }else{
                //if not logged in use tempCart to save items
                $deleteModel = new TempCartModel();

                $remove = $deleteModel->removeCartItem($productId, $uid);

                //check status of uid
                if ($remove) {
                    return $this->response->setJSON(['status' => 'success', 'message' => 'Item removed from cart']);
                } else {
                    return $this->respond([
                        'status'=> 'error',
                        'message'=> 'Failed to remove item'
                    ]);
                }
            }

        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->respond(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * 
     * Stripe Payment Related
     * 
     * 
     */


    public function payment()
    {
        $total = $this->request->getVar('total');
        $totalAmount = intval($total * 100); //stripe expects amount in cents so 5.99 = 599
        try {
            // Set Stripe API Key
            $stripeKey = getenv('STRIPE_SECRET');
            if (!$stripeKey) {
                throw new \Exception('Stripe API Key is not set in the environment variables.');
            }
            \Stripe\Stripe::setApiKey($stripeKey);
    
            // Create Checkout Session
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'Test Product',
                            ],
                            'unit_amount' => $totalAmount, 
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment', 
                'success_url' => base_url('success'), 
                'cancel_url' => base_url('cancel'),   
            ]);
    
            // Return the session URL for redirection
            return $this->response->setJSON(['url' => $session->url]);
    
        } catch (\Stripe\Exception\ApiErrorException $e) {
            log_message('error', 'Stripe Checkout error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    //stripe webhook endpoint
    public function stripeWebhook()
    {
        // Get the webhook secret from environment variables
        $endpointSecret = getenv('STRIPE_WEBHOOK_SECRET');

        // Get the payload and the signature header from the incoming request
        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? null;

        $event = null;

        try {
            // Verify the webhook signature using Stripe's SDK
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature, log and exit
            log_message('error', 'Webhook signature verification failed: ' . $e->getMessage());
            http_response_code(400);
            exit();
        } catch (\UnexpectedValueException $e) {
            // Invalid payload, log and exit
            log_message('error', 'Invalid payload: ' . $e->getMessage());
            http_response_code(400);
            exit();
        }

        // Handle the verified event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // Stripe PaymentIntent object
                
                $this->handlePaymentIntentSucceeded($paymentIntent);//payment success
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object; 
                
                $this->handlePaymentIntentFailed($paymentIntent); //payment failed
                break;
            case 'checkout.session.completed':
                $session = $event->data->object; // Stripe Checkout Session object
                
                // Update order and transaction as completed
                $this->handleCheckoutSessionCompleted($session);
                break;
            default:
                // Log or handle other event types if needed
                log_message('info', 'Received unknown event type: ' . $event->type);
        }

        // Send a 200 response to acknowledge receipt of the event
        http_response_code(200);
    }
    public function success()
    {
        // Retrieve the cookie value or create it if it doesn't exist
        $uid = $this->request->getCookie('uid');
        if (!$uid) {
            $uid = uniqid('cart_', true);
            $this->response->setCookie('uid', $uid, 60 * 60 * 24 * 7); // 1 week
        }
    
        $message = session()->getFlashdata('message');
        $pageTitle = 'Payment Success';

        // Load views with data
        return view('shop-Include/header', ['pageTitle' => $pageTitle])
        . view('success', [
            'message' => $message,
            'uid' => $uid,
        ])
        . view('shop-Include/footer');
    }
    public function cancel()
    {
        // Retrieve the cookie value or create it if it doesn't exist
        $uid = $this->request->getCookie('uid');
        if (!$uid) {
            $uid = uniqid('cart_', true);
            $this->response->setCookie('uid', $uid, 60 * 60 * 24 * 7); // 1 week
        }
    
        $message = session()->getFlashdata('message');
        $pageTitle = 'Payment Cancelled';
        // Load views with data
        return view('shop-Include/header', ['pageTitle' => $pageTitle])
        . view('cancel', [
            'message' => $message,
            'uid' => $uid,
        ])
        . view('shop-Include/footer');
    }

    /**
     * Handle successful payment intent
    */
    function handlePaymentIntentSucceeded($paymentIntent)
    {
        $transactionsModel = new TransactionsModel();

        // Update database for a successful transaction
        $transactionId = $paymentIntent->id;
        $amountReceived = $paymentIntent->amount_received;
        
        $transactionStatus = $transactionsModel->updateStatus($transactionId);
        if(!$transactionStatus){
            $message = 'Failed to update transaction status for Transaction ID.';
        }

        /* $orderStatus = $this->model->updateStatus($orderId);
        if(!$orderStatus){
            $message = 'Failed to update order status for Order ID';
        } */
        // Logic to update transactions and orders in your database
        // e.g., updateTransactionStatus($transactionId, 'success', $amountReceived);

        log_message('info', 'Payment succeeded for Transaction ID: ' . $transactionId);
    }

    /**
     * Handle failed payment intent
    */
    function handlePaymentIntentFailed($paymentIntent)
    {
        $transactionId = $paymentIntent->id;
        $errorMessage = $paymentIntent->last_payment_error->message ?? 'Unknown error';

        // Logic to handle failed payments in your database
        // e.g., updateTransactionStatus($transactionId, 'failed', $errorMessage);

        log_message('error', 'Payment failed for Transaction ID: ' . $transactionId . '. Error: ' . $errorMessage);
    }

    /**
     * Handle checkout session completion
     */
    function handleCheckoutSessionCompleted($session)
    {
        $sessionId = $session->id;
        $customerEmail = $session->customer_email;

        // Logic to update orders as completed in your database
        // e.g., updateOrderStatus($sessionId, 'completed');

        log_message('info', 'Checkout session completed for Session ID: ' . $sessionId . ' (Customer Email: ' . $customerEmail . ')');
    }
}
