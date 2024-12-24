<?php

namespace App\Controllers;

use App\Models\OrdersModel;
use App\Models\OrderTrackingModel;
use App\Models\TransactionsModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\idGenerator;

class OrderTrackingController extends ResourceController
{
    /**
     * Return View of OrderTracking 
     */
    public function index()
    {   
        $adminData = session()->get(key: 'adminData'); // Check if user is logged in
        $orderModel = new OrdersModel();
        $orders = $orderModel->findAll();  // Retrieve all orders to show in the dropdown
        $orderTrackingModel = new OrderTrackingModel();
        $ordersTracked = $orderTrackingModel->ordersTrackingList();
        $pageTitle = 'Order Tracking Index';
        return view('include/header', ['pageTitle' => $pageTitle, 'orders' => $orders, 'ordersTracked' => $ordersTracked])
         . view('include/sidebar', ['adminData' => $adminData])
         . view('include/nav')
         . view('orders/orderTracking')
         . view('include/footer');
    }

    // Method to update tracking status
    public function updateTrackingStatus()
    {
        // Retrieve data from POST request
        $data = $this->request->getPost();
        if (empty($data['order_id']) || empty($data['status'])) {
            session()->setFlashdata('error', 'Order ID and status are required.');
            return redirect()->back();
        }

                // Initialize models
        $orderModel = new OrdersModel();
        $orderTrackingModel = new OrderTrackingModel();
        $transactionsModel = new TransactionsModel();
        $idGenerator = new IdGenerator();

        // Generate a new tracking ID
        $orderTrackingId = $idGenerator->generateId();

        // Prepare tracking data
        $trackingData = [
            'order_tracking_id' => $orderTrackingId,
            'order_id' => $data['order_id'],
            'order_tracking_status' => $data['status'],
        ];
        
        /*         Add this to url to debug: ?debug=true  */
        if ($this->request->getGet('debug') === 'true') {
            return view('debug_view', ['orderId' => $data['order_id'], 'status' => $data['status']]);
        }

        try {
            // Create new order tracking record
            $createTrackingRecord = $orderTrackingModel->insert($trackingData);

            if (!$createTrackingRecord) {
                throw new \Exception('Failed to create tracking record.');
            }

            // Retrieve the newly created tracking record
            $detailsOrderTracking = $orderTrackingModel->find($createTrackingRecord);
            if (!$detailsOrderTracking) {
                throw new \Exception('Tracking record not found.');
            }
            
            // Retrieve payment status from TransactionsModel
            $payment = $transactionsModel->findTransaction($data['order_id']);
            if (!$payment) {
                throw new \Exception('Payment details not found for the order.');
            }
            log_message('debug', 'Payment details: ' . json_encode($payment));

            $paymentStatus = $payment['payment_status'];
            // Update order status if conditions are met
            if (
                $data['status'] === "Order Delivered" &&
                $paymentStatus === "completed"
            ) {
                $orderStatus = $orderModel->updateStatus(
                    $data['order_id'],
                    $data['status'],
                    $paymentStatus
                );

                if (!$orderStatus) {
                    throw new \Exception('Failed to update order status.');
                }
            }

            // Set success message
            session()->setFlashdata('success', 'Status has been updated successfully!');
        } catch (\Exception $e) {
            // Set error message
            session()->setFlashdata('error', 'Failed to update status: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    // Method to view the timeline of status changes
    public function viewTimeline($orderId)
    {
        $pageTitle = 'Order Tracking Timeline';
        $orderTrackingModel = new OrderTrackingModel();
        
        $statusChanges = $orderTrackingModel->statusChanges($orderId);
        
        return view('include/header', ['pageTitle' => $pageTitle])
        . view('include/sidebar')
        . view('include/nav')
        . view('orders/orderTimeline', ['statusChanges' => $statusChanges])
        . view('include/footer');
    }

    public function deleteStatus($id)
    {
        $orderTrackingModel = new OrderTrackingModel(); 
        $orderTrackingModel->deleteStatusById($id);
    
        return redirect()->to('/order/order-tracking')->with('success', 'successfully deleted order status');
    }

}
