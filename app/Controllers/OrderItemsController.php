<?php

namespace App\Controllers;

use App\Models\OrderItemsModel;
use App\Models\OrdersModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class OrderItemsController extends ResourceController
{

    public function orderItemDetail($orderId){
        $ordersModel = new OrdersModel();
        $orderItemsModel = new OrderItemsModel();


        $message = session()->getFlashdata('message');
        $pageTitle = 'Order Item Detail';

        // Retrieve UID from the cookie or return an error
        $uid = $this->request->getCookie('uid');
        if (!$uid) {

            return $this->response->setStatusCode(400, 'No UID cookie found');
        }
    
        if($orderId){
                $orderItems = $orderItemsModel->getItem($orderId);
        }else{
            return $this->respond([
                "status" => false,
                "message" => "user data error"
            ]);
        }
        return view('include/header', ['pageTitle' => $pageTitle]) 
        . view('include/sidebar')
        . view('include/nav')
        . view('orders/orderDetails', [
            'orderItems' => $orderItems,
            'message' => $message
        ])
        . view('include/footer'); 
    }
}
