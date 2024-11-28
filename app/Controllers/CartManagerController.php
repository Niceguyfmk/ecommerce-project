<?php

namespace App\Controllers;
use App\Models\ProductModel;
use App\Models\TempCartModel;
use App\Models\CartItemsModel;
use App\Models\CartModel;
use CodeIgniter\RESTful\ResourceController;

class CartManagerController extends ResourceController
{
    protected $tempCartController;
    protected $cartController;

    public function __construct()
    {
        $this->tempCartController = new TempCartController();
        $this->cartController = new CartItemsController();
    }

    private function getController()
    {
        $userData = session()->get('userData');
        if (!$userData) {
            $userId = $userData['user_id'];
            if($userId){
                return $this->cartController;
            }
            return $this->tempCartController;
        }
        return $this->tempCartController;
    }

    public function addItem($productId)
    {       // Check if the request is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400, 'Bad Request');
        }

        // Get the UID from the cookie (for guest users)
        $uid = $this->request->getCookie('uid');
        $userData = session()->get('userData'); // Get user data if logged in

        // If logged in, use CartItemsModel; otherwise, use TempCartModel
        if ($userData) {
            // User is logged in, use CartItemsModel
            $cartItemsModel = new CartItemsModel();
            $quantity = $this->request->getPost('quantity');
            $price = $this->request->getPost('price');

            // Validate inputs
            if (!$quantity || !$price) {
                return $this->respond(['error' => 'Invalid input'], 400);
            }

            if (!is_numeric($quantity) || $quantity <= 0) {
                return $this->respond(['error' => 'Invalid quantity value'], 400);
            }

            try {
                $product = [
                    'user_id' => $userData['user_id'], // Use logged-in user's ID
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'product_attribute_id' => 6 // Assuming a default attribute ID for now
                ];

                // Add the item to the cart (CartItems model)
                $cartItemsModel->addCartItem($product);

                return $this->respond(['message' => 'Product added to cart successfully'], 200);
            } catch (\Exception $e) {
                log_message('error', $e->getMessage());
                return $this->respond(['error' => 'Internal Server Error'], 500);
            }
        } else {
            // User is not logged in, use TempCartModel
            if (!$uid) {
                return $this->respond(['error' => 'UID cookie not found'], 400);
            }

            $tempCartModel = new TempCartModel();
            $quantity = $this->request->getPost('quantity');
            $price = $this->request->getPost('price');

            // Validate inputs
            if (!$quantity || !$price) {
                return $this->respond(['error' => 'Invalid input'], 400);
            }

            if (!is_numeric($quantity) || $quantity <= 0) {
                return $this->respond(['error' => 'Invalid quantity value'], 400);
            }

            try {
                $product = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'uid' => $uid, // Use UID for guest
                    'product_attribute_id' => 6 // Assuming a default attribute ID for now
                ];

                // Add the item to the cart (TempCart model)
                $tempCartModel->addItemsToTempCart($product);

                return $this->respond(['message' => 'Product added to cart successfully'], 200);
            } catch (\Exception $e) {
                log_message('error', $e->getMessage());
                return $this->respond(['error' => 'Internal Server Error'], 500);
            }
        }
    }
    

    public function updateItem($productId)
    {
        return $this->getController()->updateItem($productId);
    }

    public function removeItem($productId)
    {
        return $this->getController()->removeItem($productId);
    }
}
