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

    public function index()
    {
        if (!$this->user || $this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgDanger', 'Access denied');
        }

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
