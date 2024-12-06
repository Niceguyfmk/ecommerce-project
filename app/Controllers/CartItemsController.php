<?php

namespace App\Controllers;
use App\Models\ProductModel;
use App\Models\TempCartModel;
use App\Models\CartItemsModel;
use App\Models\CartModel;
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
            $cartItemsModel = new CartItemsModel();
    
            foreach ($tempCartItems as $item) {
                if($item['status'] === '0'){
                    $data = [
                        'cart_id' => $cartId,
                        'product_id' => $item['product_id'],
                        'product_attribute_id' => $item['product_attribute_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price']
                    ];
        
                    // Add item to the permanent cart, ignore duplicates
                    $cartItemsModel->addCartItem($data);
                }
            }

            // Update the temporary cart products status after transferring using uid
            $tempCartModel->upadateStatusUsingUID($uid);
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
    
    public function success()
    {
        return view('success'); // Success page view
    }

    public function cancel()
    {
        return view('cancel'); // Cancel page view
    }
}
