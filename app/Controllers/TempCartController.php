<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\TempCartModel;
use CodeIgniter\RESTful\ResourceController;

class TempCartController extends ResourceController
{  
    protected $model;

    public function __construct()
    {
        $this->model = new TempCartModel(); // Initialize the model
    }

    //cart view page
    public function viewCart() {
        
        //Set page title
        $pageTitle = "Cart";
        $subtotal = 0;
        // Get the UID from the cookie
        $uid = $this->request->getCookie('uid');
        if (!$uid) {
            return $this->response->setStatusCode(400, 'No UID cookie found');
        }

        // Retrieve all items from the cart associated with the UID
        $cartItems = $this->model->getTempCartItems($uid);

        return view('shop-Include/header', ['pageTitle' => $pageTitle])
            . view('shop/cart', ['cartItems' => $cartItems, 'subtotal' => $subtotal])
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
            $this->model->addItemsToTempCart($product);

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
