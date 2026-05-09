<?php

namespace App\Controllers;

use App\Models\ApiTokenModel;
use App\Models\UserModel;
use CodeIgniter\Config\Services;

class ApiTokens extends BaseController
{
    protected $model, $userid, $user;

    public function __construct()
    {
        $this->userid = session()->userid;
        $this->model = new UserModel();
        $this->user = $this->model->getUser($this->userid);
        $this->time = new \CodeIgniter\I18n\Time;
    }

    public function index()
    {
        $user = $this->user;
        if ($user->level != 1) {
            return redirect()->to('dashboard')->with('msgWarning', 'Access Denied!');
        }

        if ($this->request->getPost()) {
            return $this->createToken();
        }

        $tokenModel = new ApiTokenModel();
        $validation = Services::validation();

        // Get all admin users for dropdown
        $adminUsers = $this->model->where('level', 1)->findAll();

        $data = [
            'title' => 'API Tokens',
            'user' => $user,
            'time' => $this->time,
            'tokens' => $tokenModel->findAll(),
            'adminUsers' => $adminUsers,
            'validation' => $validation
        ];

        return view('Admin/api_tokens', $data);
    }

    private function createToken()
    {
        $validation = Services::validation();

        $rules = [
            'name' => [
                'label' => 'Token Name',
                'rules' => 'required|min_length[3]|max_length[255]',
            ],
            'admin_account' => [
                'label' => 'Admin Account',
                'rules' => 'required',
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('msgDanger', 'Please check the form');
        }

        $tokenModel = new ApiTokenModel();
        $token = $tokenModel->generateToken();

        $data = [
            'user_id' => $this->userid,
            'token' => $token,
            'name' => $this->request->getPost('name'),
            'admin_account' => $this->request->getPost('admin_account'),
            'status' => 1
        ];

        if ($tokenModel->insert($data)) {
            return redirect()->back()->with('msgSuccess', 'API Token created successfully');
        }

        return redirect()->back()->with('msgDanger', 'Failed to create token');
    }

    public function delete($id)
    {
        $user = $this->user;
        if ($user->level != 1) {
            return redirect()->to('dashboard')->with('msgWarning', 'Access Denied!');
        }

        $tokenModel = new ApiTokenModel();
        if ($tokenModel->delete($id)) {
            return redirect()->back()->with('msgSuccess', 'Token deleted successfully');
        }

        return redirect()->back()->with('msgDanger', 'Failed to delete token');
    }

    public function toggle($id)
    {
        $user = $this->user;
        if ($user->level != 1) {
            return redirect()->to('dashboard')->with('msgWarning', 'Access Denied!');
        }

        $tokenModel = new ApiTokenModel();
        $token = $tokenModel->find($id);

        if (!$token) {
            return redirect()->back()->with('msgDanger', 'Token not found');
        }

        $newStatus = $token->status == 1 ? 0 : 1;
        if ($tokenModel->update($id, ['status' => $newStatus])) {
            $msg = $newStatus == 1 ? 'Token activated' : 'Token deactivated';
            return redirect()->back()->with('msgSuccess', $msg);
        }

        return redirect()->back()->with('msgDanger', 'Failed to update token');
    }
}
