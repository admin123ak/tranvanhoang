<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\InvoiceModel;
use App\Models\UserModel;
use CodeIgniter\Config\Services;

class Recharge extends BaseController
{
    protected $userModel, $transactionModel, $invoiceModel, $user;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->transactionModel = new TransactionModel();
        $this->invoiceModel = new InvoiceModel();
        $this->user = $this->userModel->getUser();
    }

    public function index()
    {
        if (!$this->user) {
            return redirect()->to('login')->with('msgWarning', 'Please login first.');
        }

        $transactions = $this->transactionModel->getUserTransactions($this->user->id_users, 20);
        $invoices = $this->invoiceModel->getUserInvoices($this->user->id_users, 10);

        // Ensure arrays (findAll may return false on DB error)
        if (!is_array($transactions)) $transactions = [];
        if (!is_array($invoices)) $invoices = [];

        $data = [
            'title' => 'Recharge',
            'user' => $this->user,
            'transactions' => $transactions,
            'invoices' => $invoices,
            'validation' => Services::validation(),
        ];

        return view('User/recharge', $data);
    }

    public function createInvoice()
    {
        if (!$this->user) {
            return redirect()->to('login')->with('msgWarning', 'Please login first.');
        }

        $amount = $this->request->getPost('amount');

        $rules = [
            'amount' => [
                'label' => 'Amount',
                'rules' => 'required|numeric|greater_than_equal_to[10000]',
                'errors' => [
                    'required' => 'Please enter amount',
                    'numeric' => 'Amount must be a number',
                    'greater_than_equal_to' => 'Minimum amount is 10.000₫'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('msgDanger', 'Invalid amount. Minimum 10.000₫');
        }

        // Generate invoice
        $invoiceCode = $this->invoiceModel->generateInvoiceCode();
        $expiredAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        $invoiceData = [
            'user_id' => $this->user->id_users,
            'invoice_code' => $invoiceCode,
            'amount' => $amount,
            'status' => 'pending',
            'payment_method' => 'MBBank',
            'expired_at' => $expiredAt
        ];

        $invoiceId = $this->invoiceModel->insert($invoiceData);

        if ($invoiceId) {
            return redirect()->to('recharge/payment/' . $invoiceCode);
        }

        return redirect()->back()->with('msgDanger', 'Failed to create invoice');
    }

    public function payment($invoiceCode = null)
    {
        if (!$this->user) {
            return redirect()->to('login')->with('msgWarning', 'Please login first.');
        }

        if (!$invoiceCode) {
            return redirect()->to('recharge')->with('msgDanger', 'Invoice not found');
        }

        $invoice = $this->invoiceModel->where('invoice_code', $invoiceCode)
            ->where('user_id', $this->user->id_users)
            ->first();

        if (!$invoice) {
            return redirect()->to('recharge')->with('msgDanger', 'Invoice not found');
        }

        // Check if expired
        $now = new \CodeIgniter\I18n\Time();

        if (isset($invoice->expired_at) && $invoice->expired_at) {
            try {
                $expiredAt = \CodeIgniter\I18n\Time::parse($invoice->expired_at);

                if ($now->isAfter($expiredAt) && $invoice->status == 'pending') {
                    $this->invoiceModel->update($invoice->id_invoice, ['status' => 'expired']);
                    $invoice->status = 'expired';
                }
            } catch (\Exception $e) {
                log_message('error', 'Failed to parse expired_at: ' . $e->getMessage());
            }
        }

        $data = [
            'title' => 'Payment',
            'user' => $this->user,
            'invoice' => $invoice,
        ];

        return view('User/payment', $data);
    }

    public function checkPayment($invoiceCode = null)
    {
        if (!$this->user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        if (!$invoiceCode) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invoice not found']);
        }

        $invoice = $this->invoiceModel->where('invoice_code', $invoiceCode)
            ->where('user_id', $this->user->id_users)
            ->first();

        if (!$invoice) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invoice not found']);
        }

        // Check if already completed
        if ($invoice->status == 'completed') {
            return $this->response->setJSON([
                'success' => true,
                'status' => 'completed',
                'message' => 'Payment completed'
            ]);
        }

        // Check if expired
        $now = new \CodeIgniter\I18n\Time();

        if ($invoice->expired_at) {
            try {
                $expiredAt = \CodeIgniter\I18n\Time::parse($invoice->expired_at);

                if ($now->isAfter($expiredAt)) {
                    $this->invoiceModel->update($invoice->id_invoice, ['status' => 'expired']);
                    return $this->response->setJSON([
                        'success' => false,
                        'status' => 'expired',
                        'message' => 'Invoice expired'
                    ]);
                }
            } catch (\Exception $e) {
                log_message('error', 'Failed to parse expired_at in checkPayment: ' . $e->getMessage());
            }
        }

        // Call MBBank API to check
        $apiKey = getenv('MBBANK_API_KEY');

        if (!$apiKey) {
            return $this->response->setJSON(['success' => false, 'message' => 'API not configured']);
        }

        try {
            $client = Services::curlrequest();
            $response = $client->get("https://queenvps.com/api/historymb/{$apiKey}", [
                'timeout' => 10,
                'http_errors' => false
            ]);

            $result = json_decode($response->getBody(), true);

            if (!$result || !isset($result['success']) || !$result['success']) {
                return $this->response->setJSON(['success' => false, 'message' => 'API error']);
            }

            // Check if transaction exists
            if (isset($result['transactions']) && is_array($result['transactions'])) {
                foreach ($result['transactions'] as $txn) {
                    if ($txn['type'] !== 'IN') {
                        continue;
                    }

                    $description = $txn['description'];
                    $amount = $txn['amount'];

                    // Check if description contains invoice code
                    if (stripos($description, $invoiceCode) !== false && $amount == $invoice->amount) {
                        // Payment found! Process it
                        $db = \Config\Database::connect();
                        $db->transStart();

                        try {
                            // Update invoice status
                            $this->invoiceModel->update($invoice->id_invoice, ['status' => 'completed']);

                            // Add transaction record
                            $this->transactionModel->insert([
                                'user_id' => $this->user->id_users,
                                'amount' => $amount,
                                'type' => 'IN',
                                'description' => $description,
                                'transaction_date' => $txn['formatted_date'],
                                'status' => 'completed'
                            ]);

                            // Update user balance
                            $currentUser = $this->userModel->find($this->user->id_users);
                            $newBalance = $currentUser->saldo + $amount;
                            $this->userModel->update($this->user->id_users, ['saldo' => $newBalance]);

                            $db->transComplete();

                            if ($db->transStatus() === false) {
                                return $this->response->setJSON(['success' => false, 'message' => 'Transaction failed']);
                            }

                            return $this->response->setJSON([
                                'success' => true,
                                'status' => 'completed',
                                'message' => 'Payment successful! Balance updated.',
                                'new_balance' => number_format($newBalance, 0, ',', '.') . '₫'
                            ]);

                        } catch (\Exception $e) {
                            $db->transRollback();
                            log_message('error', 'Payment processing error: ' . $e->getMessage());
                            return $this->response->setJSON(['success' => false, 'message' => 'Processing error']);
                        }
                    }
                }
            }

            // Payment not found yet
            return $this->response->setJSON([
                'success' => false,
                'status' => 'pending',
                'message' => 'Payment not found. Please complete the transfer.'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'MBBank check error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'API error']);
        }
    }

    /**
     * Auto check MBBank transactions via cron
     */
    public function autoCheck()
    {
        $apiKey = getenv('MBBANK_API_KEY');

        if (!$apiKey) {
            log_message('error', 'MBBank API key not configured');
            return $this->response->setJSON(['success' => false, 'message' => 'API key not configured']);
        }

        try {
            $client = Services::curlrequest();
            $response = $client->get("https://queenvps.com/api/historymb/{$apiKey}", [
                'timeout' => 10,
                'http_errors' => false
            ]);

            $result = json_decode($response->getBody(), true);

            if (!$result || !isset($result['success']) || !$result['success']) {
                log_message('error', 'MBBank API returned error');
                return $this->response->setJSON(['success' => false, 'message' => 'API error']);
            }

            $processedCount = 0;
            $addedAmount = 0;

            if (isset($result['transactions']) && is_array($result['transactions'])) {
                foreach ($result['transactions'] as $txn) {
                    if ($txn['type'] !== 'IN') {
                        continue;
                    }

                    $amount = $txn['amount'];
                    $description = $txn['description'];
                    $transactionDate = $txn['formatted_date'];

                    // Check for invoice code pattern (IV + numbers)
                    if (preg_match('/IV\d+/i', $description, $matches)) {
                        $invoiceCode = $matches[0];

                        $invoice = $this->invoiceModel->where('invoice_code', $invoiceCode)
                            ->where('status', 'pending')
                            ->where('amount', $amount)
                            ->first();

                        if ($invoice) {
                            // Check if already processed
                            $existing = $this->transactionModel->checkDuplicateTransaction(
                                $invoice->user_id,
                                $amount,
                                $description,
                                $transactionDate
                            );

                            if ($existing) {
                                continue;
                            }

                            $db = \Config\Database::connect();
                            $db->transStart();

                            try {
                                $this->invoiceModel->update($invoice->id_invoice, ['status' => 'completed']);

                                $this->transactionModel->insert([
                                    'user_id' => $invoice->user_id,
                                    'amount' => $amount,
                                    'type' => 'IN',
                                    'description' => $description,
                                    'transaction_date' => $transactionDate,
                                    'status' => 'completed'
                                ]);

                                $currentUser = $this->userModel->find($invoice->user_id);
                                $newBalance = $currentUser->saldo + $amount;
                                $this->userModel->update($invoice->user_id, ['saldo' => $newBalance]);

                                $db->transComplete();

                                if ($db->transStatus() !== false) {
                                    $processedCount++;
                                    $addedAmount += $amount;
                                    log_message('info', "Auto-processed invoice {$invoiceCode}, amount: {$amount}₫");
                                }
                            } catch (\Exception $e) {
                                $db->transRollback();
                                log_message('error', 'Auto-check processing error: ' . $e->getMessage());
                            }
                        }
                    }
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'processed' => $processedCount,
                'total_amount' => $addedAmount,
                'message' => "Processed {$processedCount} invoices, total: " . number_format($addedAmount, 0, ',', '.') . "₫"
            ]);

        } catch (\Exception $e) {
            log_message('error', 'MBBank auto-check error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
