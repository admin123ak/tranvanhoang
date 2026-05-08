<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table      = 'invoices';
    protected $primaryKey = 'id_invoice';
    protected $allowedFields = ['user_id', 'invoice_code', 'amount', 'status', 'payment_method', 'expired_at'];
    protected $returnType = 'object';
    protected $useTimestamps = true;

    public function generateInvoiceCode()
    {
        // Generate unique invoice code: IV + timestamp + random
        do {
            $code = 'IV' . time() . rand(100, 999);
            $exists = $this->where('invoice_code', $code)->first();
        } while ($exists);

        return $code;
    }

    public function getUserInvoices($user_id, $limit = 10)
    {
        return $this->where('user_id', $user_id)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getPendingInvoice($invoice_code)
    {
        return $this->where('invoice_code', $invoice_code)
            ->where('status', 'pending')
            ->first();
    }
}
