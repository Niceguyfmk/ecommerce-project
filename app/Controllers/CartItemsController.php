<?php

namespace App\Controllers;
use App\Models\ProductModel;
use App\Models\TempCartModel;
use App\Models\CartItemsModel;
use App\Models\CartModel;
use CodeIgniter\RESTful\ResourceController;

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
        $tempCartItems = $tempCartModel->getTempCartItems($uid);
    
        if (!empty($tempCartItems)) {
            $cartItemsModel = new CartItemsModel();
    
            foreach ($tempCartItems as $item) {
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
    
            // Clear the temporary cart after transferring
            $tempCartModel->clearTempCart($uid);
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
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400, 'Bad Request');
        }

        // Get the UID from the cookie
        $uid = $this->request->getCookie('uid');
        if (!$uid) {
            return $this->respond(['error' => 'UID cookie not found'], 400);
        }

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
            $cartItemsModel = new CartItemsModel();

            $cartId = null;

            if ($userData && isset($userData['user_id'])) {
                // Fetch cart for logged-in user
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
            } else {
                // Fetch cart for guest using UID
                $cart = $cartModel->where('uid', $uid)->first();

                if (!$cart) {
                    // Create a new cart for guest
                    $cartId = $cartModel->insert([
                        'uid' => $uid,
                        'coupon_id' => null,
                    ], true);
                } else {
                    $cartId = $cart['cart_id'];
                }
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

        $quantity = $this->request->getPost('quantity');
        $quantity = (int) $quantity;

        if (!$quantity || $quantity < 1) {
            return $this->response->setStatusCode(400, 'Invalid Quantity');
        }

        // Get the UID from the cookie
        $uid = $this->request->getCookie('uid');
        if (!$uid) {
            return $this->respond(['error' => 'UID cookie not found'], 400);
        }

        // Update the cart item in the model
        $updateStatus = $this->model->updateCartItem($productId, $uid, $quantity);

        if ($updateStatus) {
            // Respond with success if the update was successful
            return $this->response->setJSON(['status' => 'success', 'message' => 'Cart item updated successfully']);
        } else {
            // Respond with error if the update failed
            return $this->response->setStatusCode(500, 'Error updating cart item');
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

        $remove = $this->model->removeCartItem($productId, $uid);

        if ($remove) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Item removed from cart']);
        } else {
            return $this->respond([
                'status'=> 'error',
                'message'=> 'Failed to remove item'
            ]);
        }
    }
}
