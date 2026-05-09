<?php

namespace App\Controllers;

use App\Models\GetkeyConfigModel;
use App\Models\UserModel;
use App\Models\PackageModel;
use CodeIgniter\Config\Services;

class GetkeyConfig extends BaseController
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
        if ($this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgWarning', 'Access Denied!');
        }

        $configModel = new GetkeyConfigModel();
        $config = $configModel->first();
        $adminUsers = $this->model->where('level', 1)->findAll();
        $packageModel = new PackageModel();
        $packages = $packageModel->findAll();

        $data = [
            'title' => 'GetKey Config',
            'user' => $this->user,
            'time' => $this->time,
            'config' => $config,
            'adminUsers' => $adminUsers,
            'packages' => $packages,
            'validation' => Services::validation()
        ];

        return view('Admin/getkey_config', $data);
    }

    public function save()
    {
        if ($this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgWarning', 'Access Denied!');
        }

        $validation = Services::validation();
        $rules = [
            'admin_account' => ['label' => 'Admin Account', 'rules' => 'required'],
            'package_id' => ['label' => 'Package', 'rules' => 'required|integer'],
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('admin/getkey-config')->withInput()->with('msgDanger', 'Please check the form');
        }

        $configModel = new GetkeyConfigModel();
        $existing = $configModel->first();

        $data = [
            'admin_account' => $this->request->getPost('admin_account'),
            'package_id' => $this->request->getPost('package_id'),
            'price_per_hour' => $this->request->getPost('price_per_hour') ?: 0,
            'max_hours' => $this->request->getPost('max_hours') ?: 720,
            'max_devices' => $this->request->getPost('max_devices') ?: 1,
            'youmoney_token' => $this->request->getPost('youmoney_token') ?: null,
            'status' => 1,
        ];

        if ($existing) {
            $configModel->update($existing->id, $data);
        } else {
            $configModel->insert($data);
        }

        return redirect()->to('admin/getkey-config')->with('msgSuccess', 'Config saved successfully');
    }
}
