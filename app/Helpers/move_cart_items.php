<?php
// app/Helpers/move_cart_items.php

if (!function_exists('move_cart_items')) {
    function move_cart_items($uid, $cartId) {
        $db = \Config\Database::connect();
        $tempCartModel = new \App\Models\TempCartModel();

        // Fetch temp cart items
        $tempCartItems = $tempCartModel->getTempCartItems($uid);

        if (!empty($tempCartItems)) {
            // Start database transaction
            $db->transStart();

            // Move each item from temporary cart to permanent cart
            foreach ($tempCartItems as $item) {
                $permCartModel = new \App\Models\CartItemsModel();
                if($item['status'] === '0'){
                    $data = [
                        'cart_id' => $cartId,
                        'product_id' => $item['product_id'],
                        'product_attribute_id' => $item['product_attribute_id'],
                        'quantity' => $item['quantity'],
                        'uid' => $uid,
                        'price' => $item['price']
                    ];
                    $permCartModel->addCartItem($data);
                }   
            }

            $result = $tempCartModel->upadateStatusUsingUID($uid);
            if (!$result) {
                log_message('error', 'Failed to update the status of temporary cart items for UID: ' . $uid);
            }

            // After moving items, clear the temporary cart
            $tempCartModel->clearTempCart($uid);

            // Commit the transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                $db->transRollback();
                return 'Transaction failed!';
            } else {
                return 'Items moved successfully!';
            }
        } else {
            return 'No items to move!';
        }
    }
}
