<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Config\Services;

class BankAccount extends BaseController
{
    protected $userModel, $user;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->user = $this->userModel->getUser();
    }

    private function ensureTable()
    {
        $db = \Config\Database::connect();

        // Create table if not exists
        try {
            $db->query("CREATE TABLE IF NOT EXISTS `bank_accounts` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `bank_name` varchar(100) NOT NULL,
                `account_number` varchar(50) NOT NULL,
                `account_name` varchar(100) NOT NULL,
                `api_token` varchar(255) DEFAULT NULL,
                `status` tinyint(1) NOT NULL DEFAULT '1',
                `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        } catch (\Exception $e) {
            log_message('error', 'Failed to create bank_accounts table: ' . $e->getMessage());
        }

        // Check if table has data
        $count = 0;
        try {
            $row = $db->query("SELECT COUNT(*) as cnt FROM bank_accounts")->getRow();
            $count = $row->cnt ?? 0;
        } catch (\Exception $e) {
            log_message('error', 'Failed to count bank_accounts rows: ' . $e->getMessage());
        }

        // Insert default data if empty
        if ($count == 0) {
            try {
                $db->query("INSERT INTO `bank_accounts` (`bank_name`, `account_number`, `account_name`, `api_token`, `status`) VALUES
                    ('MBBank', '0868641019', 'TRẦN VĂN HOÀNG', 'MB_FREE_021FA4D804026B08', 1)");
                log_message('info', 'Inserted default bank account');
            } catch (\Exception $e) {
                log_message('error', 'Failed to insert default bank account: ' . $e->getMessage());
            }
        }
    }

    public function index()
    {
        if (!$this->user || $this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgDanger', 'Access denied');
        }

        $this->ensureTable();

        $db = \Config\Database::connect();
        $builder = $db->table('bank_accounts');
        $accounts = $builder->orderBy('id', 'DESC')->get()->getResult();

        $data = [
            'title' => 'Bank Account Management',
            'user' => $this->user,
            'accounts' => $accounts,
            'validation' => Services::validation(),
        ];

        return view('Admin/bank_accounts', $data);
    }

    public function create()
    {
        if (!$this->user || $this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgDanger', 'Access denied');
        }

        $rules = [
            'bank_name' => 'required|min_length[2]|max_length[100]',
            'account_number' => 'required|min_length[6]|max_length[50]',
            'account_name' => 'required|min_length[2]|max_length[100]',
            'api_token' => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('msgDanger', 'Invalid input');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('bank_accounts');

        $data = [
            'bank_name' => $this->request->getPost('bank_name'),
            'account_number' => $this->request->getPost('account_number'),
            'account_name' => $this->request->getPost('account_name'),
            'api_token' => $this->request->getPost('api_token'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($builder->insert($data)) {
            return redirect()->to('admin/bank-accounts')->with('msgSuccess', 'Bank account added successfully');
        }

        return redirect()->back()->with('msgDanger', 'Failed to add bank account');
    }

    public function edit($id)
    {
        if (!$this->user || $this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgDanger', 'Access denied');
        }

        $rules = [
            'bank_name' => 'required|min_length[2]|max_length[100]',
            'account_number' => 'required|min_length[6]|max_length[50]',
            'account_name' => 'required|min_length[2]|max_length[100]',
            'api_token' => 'permit_empty|max_length[255]',
            'status' => 'required|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('msgDanger', 'Invalid input');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('bank_accounts');

        $data = [
            'bank_name' => $this->request->getPost('bank_name'),
            'account_number' => $this->request->getPost('account_number'),
            'account_name' => $this->request->getPost('account_name'),
            'api_token' => $this->request->getPost('api_token'),
            'status' => $this->request->getPost('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($builder->where('id', $id)->update($data)) {
            return redirect()->to('admin/bank-accounts')->with('msgSuccess', 'Bank account updated successfully');
        }

        return redirect()->back()->with('msgDanger', 'Failed to update bank account');
    }

    public function delete($id)
    {
        if (!$this->user || $this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgDanger', 'Access denied');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('bank_accounts');

        if ($builder->where('id', $id)->delete()) {
            return redirect()->to('admin/bank-accounts')->with('msgSuccess', 'Bank account deleted successfully');
        }

        return redirect()->back()->with('msgDanger', 'Failed to delete bank account');
    }
}
