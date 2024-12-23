<?php

namespace App\Models;

use CodeIgniter\Model;

class CouponModel extends Model
{
    protected $table            = 'coupons';
    protected $primaryKey       = 'coupon_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "code",
        "discount_type",
        "discount_value",
        "expiry_date",
        "min_order_amount",
        "max_usage",
        "max_discount_value"
    ];

    public function addCoupon($data){
        return $this->insert($data);
    }

    public function getCouponByCode($code){
        return $this->where("code",$code)->first();
    } 
    
    public function getCouponId($couponCode){
        $coupon = $this->where("code", $couponCode)->first();
        return $coupon['coupon_id'];
    }

    public function updateCouponUsageById($coupon_id)
    {
        // Find the coupon by ID
        $coupon = $this->find($coupon_id);
    
        // Check if the coupon exists
        if (!$coupon) {
            throw new \RuntimeException('Coupon not found with ID: ' . $coupon_id);
        }
    
        // Decrement max usage
        $usage = $coupon['max_usage'];
    
        if ($usage <= 0) {
            throw new \RuntimeException('Max usage for coupon has already reached zero.');
        }
    
        //echo "max usage before: " . $usage;
        $usage--;
        //echo "max usage after: " . $usage;
    
        $data = [
            'coupon_id' => $coupon_id, 
            'max_usage' => $usage,
        ];
    
        // Save the updated coupon
        return $this->save($data);
    }

    public function getAllCoupons(){
        return $this->findAll();
    }
    
}
