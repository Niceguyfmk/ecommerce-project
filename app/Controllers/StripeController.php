<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Stripe;

class StripeController extends BaseController
{
    public function __construct(){
        helper(["url"]);
    }

    public function stripe()
    {
        return view('stripe');
    }

    public function payment()
    {
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
                            'unit_amount' => 5 * 100, 
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
