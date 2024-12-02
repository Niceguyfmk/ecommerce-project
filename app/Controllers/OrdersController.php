<?php

namespace App\Controllers;
use App\Models\CartItemsModel;
use App\Models\CartModel;
use App\Models\OrderItemsModel;
use App\Models\OrdersModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class OrdersController extends ResourceController
{
    public function __construct(){
        $this->model = new OrdersModel();
    }

    public function create()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $grandTotal = $data['grand_total'];
        $paymentMethod = $data['payment_method'];

        //get the cookies
        $uid = $this->request->getCookie('uid');

        // Check if the user is logged in
        $userData = session()->get('userData');
        $userId = $userData['user_id'];

        $cartItems = [];
    
        if ($userData && isset($userData['user_id'])) {
            
            $cartModel = new CartModel();
            $cart = $cartModel->where('user_id', $userId)->first();
            $cartId = $cart['cart_id'];
            $cartItemsModel = new CartItemsModel();
            $cartItems = $cartItemsModel->getUserCart($cartId);
        }

            // Create the order
        $orderModel = new OrdersModel();
        $orderData = [
            'user_id' => $userId,
            'total_amount' => $grandTotal,
            'status' => 'pending',
            'payment_method' => $paymentMethod,
            'coupon_id' => NULL
        ];

        $orderId = $orderModel->createOrder($orderData);  
        if (!$orderId) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to create order.']);
        }
        // Add items to the order
        $orderItemsModel = new OrderItemsModel();
        foreach ($cartItems as $item) {
            $insertSuccess = $orderItemsModel->insert([
                'order_id' => $orderId,
                'product_attribute_id' => $item['product_attribute_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            if (!$insertSuccess) {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to add order items.']);
            }
        }
        
        // Simulate order processing logic here
        return $this->response->setStatusCode(201)->setJSON(['message' => 'Order created successfully']);
    }
    
    
}