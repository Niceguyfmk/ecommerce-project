<?php

namespace App\Controllers;
use App\Models\ProductModel;
use App\Models\TempCartModel;
use App\Models\CartItemsModel;
use App\Models\CartModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class CartItemsController extends ResourceController
{
    public function __construct()
    {
        $this->model = new CartItemsModel(); 
    }

    //cart view page
    public function checkout()
    {
        $message = session()->getFlashdata('message');
        $pageTitle = 'Checkout';
        $userData = session()->get('userData');
        // Check if user is logged in
        if (!$userData) {
            // Redirect to login page if not logged in
            return redirect()->to('user/login')->with('error', 'You must be logged in to checkout.');
        }
    
        // User is logged in, get the UID from the cookies
        $uid = $this->request->getCookie('uid');
        if (!$uid) {
            return $this->response->setStatusCode(400, 'No UID cookie found');
        }

        $userId = $userData['user_id']; 

        // Check if a cart already exists for the user
        $cartModel = new CartModel();
        $cart = $cartModel->where('user_id', $userId)->first();

        // If no cart exists, create one
        if (!$cart) {
            $cart = $cartModel->insert([
                'user_id' => $userId,
                'coupon_id' => null,  // or set any default coupon ID
            ]);
        }
        
        // Get the user's temporary cart items
        $tempCartModel = new TempCartModel();
        $tempCartItems = $tempCartModel->getTempCartItems($uid);
    
        // Check if there are items in the temporary cart
        if (!empty($tempCartItems)) {
            // Transfer the temporary cart items to the permanent cart table
            $cartItemsModel = new CartItemsModel();
            foreach ($tempCartItems as $item) {
                $data = [
                    'cart_id'=>$cart['cart_id'],
                    'uid' => $uid,
                    'product_id'=> $item['product_id'],
                    'product_attribute_id'=> $item['product_attribute_id'],
                    'quantity'=> $item['quantity'],
                    'price'=> $item['price']
                ];
                $cartItemsModel->addCartItem($data);
            }
    
            // Optionally: Clear the temporary cart after transferring
            //$tempCartModel->clearTempCart($uid);
        }
    
        // Now, load the checkout page and pass the cart items
        $cartItems = $cartItemsModel->getUserCart($uid);
    
        return view('shop-Include/header', ['pageTitle' => $pageTitle]) 
        . view('shop/checkout', ['cartItems' => $cartItems])
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
            $product = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
                'uid' => $uid, // Use UID instead of session_id
                'product_attribute_id' => 6
            ];

            // Add the item to the cart (TempCart model)
            $this->model->addCartItem($product);

            // Respond with the updated cart count
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
