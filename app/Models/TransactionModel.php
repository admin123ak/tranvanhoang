<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table      = 'transactions';
    protected $primaryKey = 'id_transaction';
    protected $allowedFields = ['user_id', 'amount', 'type', 'description', 'transaction_date', 'status'];
    protected $useTimestamps = true;

    public function getUserTransactions($user_id, $limit = 10)
    {
        return $this->where('user_id', $user_id)
            ->orderBy('transaction_date', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function checkDuplicateTransaction($user_id, $amount, $description, $transaction_date)
    {
        return $this->where('user_id', $user_id)
            ->where('amount', $amount)
            ->where('description', $description)
            ->where('transaction_date', $transaction_date)
            ->first();
    }
}
