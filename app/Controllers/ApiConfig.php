<?php

namespace App\Controllers;

use App\Models\ApiConfigModel;
use App\Models\UserModel;
use App\Models\PackageModel;
use CodeIgniter\Config\Services;

class ApiConfig extends BaseController
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
            return $this->saveConfig();
        }

        $configModel = new ApiConfigModel();
        $packageModel = new PackageModel();
        $validation = Services::validation();

        $configs = $configModel->findAll();
        $packages = $packageModel->findAll();
        $adminUsers = $this->model->where('level', 1)->findAll();

        $data = [
            'title' => 'API Configuration',
            'user' => $user,
            'time' => $this->time,
            'configs' => $configs,
            'packages' => $packages,
            'adminUsers' => $adminUsers,
            'validation' => $validation
        ];

        return view('Admin/api_config', $data);
    }

    private function saveConfig()
    {
        $configModel = new ApiConfigModel();
        $validation = Services::validation();

        $rules = [
            'admin_account' => ['label' => 'Admin Account', 'rules' => 'required'],
            'package_id' => ['label' => 'Package', 'rules' => 'required|integer'],
            'price_per_hour' => ['label' => 'Price per hour', 'rules' => 'required|numeric|greater_than[0]'],
            'max_hours' => ['label' => 'Max hours', 'rules' => 'required|integer|greater_than[0]'],
            'max_devices' => ['label' => 'Max devices', 'rules' => 'required|integer|greater_than[0]'],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('msgDanger', 'Please check the form');
        }

        $data = [
            'admin_account' => $this->request->getPost('admin_account'),
            'package_id' => $this->request->getPost('package_id'),
            'price_per_hour' => $this->request->getPost('price_per_hour'),
            'min_hours' => 1,
            'max_hours' => $this->request->getPost('max_hours'),
            'max_devices' => $this->request->getPost('max_devices'),
            'status' => 1
        ];

        if ($configModel->insert($data)) {
            return redirect()->back()->with('msgSuccess', 'Configuration saved successfully');
        }

        return redirect()->back()->with('msgDanger', 'Failed to save configuration');
    }

    public function delete($id)
    {
        if ($this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgWarning', 'Access Denied!');
        }

        $configModel = new ApiConfigModel();
        $configModel->delete($id);

        return redirect()->back()->with('msgSuccess', 'Configuration deleted');
    }

    public function toggle($id)
    {
        if ($this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgWarning', 'Access Denied!');
        }

        $configModel = new ApiConfigModel();
        $config = $configModel->find($id);
        if (!$config) {
            return redirect()->back()->with('msgDanger', 'Config not found');
        }

        $newStatus = $config->status == 1 ? 0 : 1;
        $configModel->update($id, ['status' => $newStatus]);

        return redirect()->back()->with('msgSuccess', $newStatus == 1 ? 'Config activated' : 'Config deactivated');
    }
}
