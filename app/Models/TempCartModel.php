<?php

namespace App\Models;

use CodeIgniter\Model;

class TempCartModel extends Model
{
    protected $table            = 'temp_cart_items';
    protected $primaryKey       = 'temp_cart_item_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['uid', 'product_attribute_id', 'quantity', 'price', 'product_id'];

    public function addItemsToTempCart($data)
    {
        // Check if the item already exists in the cart based on UID and product ID
        $existingItem = $this->where(['product_id' => $data['product_id'], 'uid' => $data['uid']])->first();

        if ($existingItem) {
            // If the item exists, update the quantity instead of inserting a new one
            $newQuantity = $existingItem['quantity'] + $data['quantity']; // Add the new quantity to existing quantity
            return $this->update($existingItem['temp_cart_item_id'], ['quantity' => $newQuantity]);
        } else {
            // Insert the new item into the cart
            return $this->insert($data);
        }
    }

    public function updateCartItem($productId, $uid, $quantity)
    {
        // Find the cart item based on product ID and UID
        $cartItem = $this->where('product_id', $productId)
                         ->where('uid', $uid)
                         ->first();

        if (!$cartItem) {

            // If the item doesn't exist in the cart, return an error
            return false; 
        }

        // Update the quantity in the cart item
        $cartItem['quantity'] = $quantity;

        // Save the updated cart item
        return $this->save($cartItem); // Return the result of save operation (true/false)
    }

    public function removeCartItem($productId, $uid)
    {
        return $this->where('product_id', $productId)
                    ->where('uid', $uid)
                    ->delete();
    }

    public function getTempCartItems($uid) {
        // Retrieve all cart items based on UID
        /* return $this->where('uid', $uid)->findAll(); */
        $builder = $this->db->table('temp_cart_items');
        $builder->select('temp_cart_items.*, products.name as product_name, images.image_url as product_image');
        $builder->join('product_attributes', 'temp_cart_items.product_attribute_id = product_attributes.product_attribute_id');
        $builder->join('products', 'temp_cart_items.product_id = products.product_id');
        $builder->join('images', 'products.product_id = images.product_id');
        $builder->where('temp_cart_items.uid', $uid);
        return $builder->get()->getResultArray();
    }

    public function clearTempCart($uid)
    {
        // Delete all items associated with the provided UID
        return $this->where('uid', $uid)->delete();
    }

    public function getCartStatus($productId, $uid){

        // Find the cart item based on product ID and UID
        $cartItem = $this->where('product_id', $productId)
                         ->where('uid', $uid)
                         ->first();

        if (!$cartItem) {

            // If the item doesn't exist in the cart, return an error
            return false; 
        }
        
        $status = "";
        $status = $cartItem['status'];

        return $status;
    }
}
