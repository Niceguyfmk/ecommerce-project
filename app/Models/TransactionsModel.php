<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionsModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'transaction_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'amount',
        'payment_status',
        'payment_date'
    ];

    public function createTransaction($transactionData){
        return $this->insert($transactionData);
    }
    public function updatePayment($order_id, $data){
        return $this->update($order_id, $data);

    }
    //updating transaction status
    public function updateStatus($transaction_id){
        
        $data = ['payment_status' => 'completed']; 
        return $this->update($transaction_id, $data);
    }

}
