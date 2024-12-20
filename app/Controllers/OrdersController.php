<?php

namespace App\Controllers;
use App\Models\CartItemsModel;
use App\Models\CartModel;
use App\Models\CouponModel;
use App\Models\OrderItemsModel;
use App\Models\OrdersModel;
use App\Models\TransactionsModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\idGenerator;

class OrdersController extends ResourceController
{
    public function __construct(){
        $this->model = new OrdersModel();
    }

    public function create()
    {
        $IdGenerator = new idGenerator();
        $data = json_decode(file_get_contents('php://input'), true);
    
        // Validate incoming data
        if (!isset($data['grand_total'], $data['payment_method'])) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing required fields.']);
        }
    
        $grandTotal = $data['grand_total'];
        $paymentMethod = $data['payment_method'];
        $couponId = $data['coupon_id'] ?? null;
    
        $uniqueOrderId = $IdGenerator->generateId();
        $userData = session()->get('userData');
    
        if (!$userData || !isset($userData['user_id'])) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'User not logged in.']);
        }
    
        $userId = $userData['user_id'];
    
        // Fetch the user's cart
        $cartModel = new CartModel();
        $cart = $cartModel->where('user_id', $userId)->first();
    
        if (!$cart) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Cart not found.']);
        }
    
        $cartId = $cart['cart_id'];
        $cartItemsModel = new CartItemsModel();
        $cartItems = $cartItemsModel->getUserCart($cartId);
    
        if (empty($cartItems)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Cart is empty.']);
        }
    
        // Create the order
        $orderModel = new OrdersModel();
        $orderData = [
            'unique_order_id' => $uniqueOrderId,
            'user_id' => $userId,
            'total_amount' => $grandTotal,
            'payment_method' => $paymentMethod,
            'coupon_id' => $couponId
        ];
    
        $orderId = $orderModel->createOrder($orderData);
        if (!$orderId) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to create order.']);
        }
    
        // Update coupon usage if applicable
        if ($couponId) {
            $couponModel = new CouponModel();
            $couponModel->updateCouponUsageById($couponId);
        }
    
        // Record transaction
        $transactionsModel = new TransactionsModel();
        $transactionData = [
            'order_id' => $orderId,
            'amount' => $grandTotal,
        ];
    
        $transactionId = $transactionsModel->createTransaction($transactionData);
    
        // Add items to the order
        $orderItemsModel = new OrderItemsModel();
        foreach ($cartItems as $item) {
            $insertSuccess = $orderItemsModel->insert([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'product_attribute_id' => $item['product_attribute_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
    
            if (!$insertSuccess) {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to add order items.']);
            }
        }
    
        // Clear the cart
        $cartItemsModel->where('cart_id', $cartId)->delete();
        $cartModel->where('cart_id', $cartId)->delete();
    
        // Redirect based on payment method
        if ($paymentMethod === "COD") {
            log_message('debug', 'COD payment method reached. Redirecting to success.');
            return $this->response->setJSON(['url' => '/success']);
        }
    
        // For other payment methods, you can handle redirection or further actions
        return $this->response->setJSON(['message' => 'Order created successfully.', 'url' => '/payment']);
    }
    
    public function viewTable(){
        
        $message = session()->getFlashdata('message');
        $pageTitle = 'Orders Table';

            /* if ($userData && isset($userData['user_id'])) {
                $userId = $userData['user_id'];

                $order = $this->model->where('user_id', $userId)->first();

                if($order){
                    $orderId = $order['order_id'];
                    $orders = $this->model->getOrders($orderId);
                }
            }else{
                return $this->respond([
                    "status" => false,
                    "message" => "user data error"
                ]);
            } */

        $orders = $this->model->getOrders();
        return view('include/header', ['pageTitle' => $pageTitle]) 
        . view('include/sidebar')
        . view('include/nav')
        . view('orders/ordersTable', [
            'orders' => $orders,
            'message' => $message
        ])
        . view('include/footer'); 
    }
    
    public function orderStatusUpdate($order_id){

        $orderStatus = $this->request->getPost('order_status');

        if (!$orderStatus) {
            return $this->response->setStatusCode(400, 'Bad Request')->setJSON(['error' => 'Order status is required']);
        }

        $order = $this->model->updateOrder($order_id, ['status' => $orderStatus]);

        if ($orderStatus === 'completed') {
            $cartModel = new CartModel();
            $cartItemsModel = new CartItemsModel();
    
            // Retrieve user_id associated with the order
            $order = $this->model->find($order_id);
            $userId = $order['user_id'];
    
            // Get user's cart
            $cart = $cartModel->where('user_id', $userId)->first();
            if ($cart) {
                $cartId = $cart['cart_id'];
    
                // Delete cart items first
                $cartItemsModel->where('cart_id', $cartId)->delete();
    
                // Then delete the cart
                $cartModel->delete($cartId);
            }
        }

        if ($order) {
            return redirect()->to('order/viewOrders')->with('message', 'status updated successfully!');
        }
    }

   /*  public function success($transactionID, $orderID){
        $transactionsModel = new TransactionsModel();
        $ordersModel = new OrdersModel();
        $pageTitle = 'Payment Successful';

        $transactionStatus = $transactionsModel->updateStatus($transactionID);
        if(!$transactionStatus){
            $message = 'Failed to update transaction status for Transaction ID.';
        }

        $orderStatus = $ordersModel->updateStatus($orderID);
        if(!$orderStatus){
            $message = 'Failed to update order status for Order ID';
        }

        $message = 'Successfully updated both status';
        return view('include/header', ['pageTitle' => $pageTitle]) 
        . view('include/sidebar')
        . view('include/nav')
        . view('success', ['message' => $message])
        . view('include/footer'); 
    } */

    public function deleteOrder($order_id){
        $ordersModel = new OrdersModel(); 
        $ordersModel->deleteOrder($order_id);
    
        return redirect()->to('/order/viewOrders')->with('success', 'successfully deleted order');
    }
    
}