<?php

namespace App\Models;

use CodeIgniter\Model;

class CartItemsModel extends Model
{
    protected $table            = 'cart_items';
    protected $primaryKey       = 'cart_item_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = 
    [
        'uid',
        'cart_id',
        'product_attribute_id',
        'product_id',
        'quantity',
        'price'
    ];

    public function getUserCart($cart_id) {
        // Retrieve all cart items based on UID
        /* return $this->where('uid', $uid)->findAll(); */
        $builder = $this->db->table('cart_items');
        $builder->select('cart_items.*, products.name as product_name, images.image_url as product_image');
        $builder->join('product_attributes', 'cart_items.product_attribute_id = product_attributes.product_attribute_id');
        $builder->join('products', 'cart_items.product_id = products.product_id');
        $builder->join('images', 'products.product_id = images.product_id');
        $builder->where('cart_items.cart_id', $cart_id);
        return $builder->get()->getResultArray();
    }

    public function addCartItem($data)
    {
        // Check if the item already exists in the cart based on UID and product ID
        $existingItem = $this->where(['product_id' => $data['product_id'], 'uid' => $data['uid']])->first();
        if ($existingItem) {
            // If the item exists, update the quantity instead of inserting a new one
            $newQuantity = $existingItem['quantity'] + $data['quantity']; // Add the new quantity to existing quantity
            return $this->update($existingItem['cart_item_id'], ['quantity' => $newQuantity]);
        } else {
            // Insert the new item into the cart
            return $this->insert($data);
        }
        
    }

    public function updateCartItem($productId, $quantity)
    {
        // Find the cart item based on product ID and UID
        $cartItem = $this->where('product_id', $productId)
                         ->first();

        if (!$cartItem) {
            // If the item doesn't exist in the cart, return an error
            return false; // Indicate failure
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
    
}
