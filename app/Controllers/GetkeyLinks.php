<?php

namespace App\Controllers;

use App\Models\GetkeyLinkModel;
use App\Models\UserModel;
use App\Models\PackageModel;
use CodeIgniter\Config\Services;

class GetkeyLinks extends BaseController
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

        $linkModel = new GetkeyLinkModel();
        $links = $linkModel->findAll();
        $adminUsers = $this->model->where('level', 1)->findAll();
        $packageModel = new PackageModel();
        $packages = $packageModel->findAll();

        $data = [
            'title' => 'GetKey Links',
            'user' => $this->user,
            'time' => $this->time,
            'links' => $links,
            'adminUsers' => $adminUsers,
            'packages' => $packages,
            'validation' => Services::validation()
        ];

        return view('Admin/getkey_links', $data);
    }

    public function create()
    {
        if ($this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgWarning', 'Access Denied!');
        }

        $validation = Services::validation();
        $rules = [
            'name' => ['label' => 'Name', 'rules' => 'required|min_length[3]|max_length[255]'],
            'admin_account' => ['label' => 'Admin Account', 'rules' => 'required'],
            'package_id' => ['label' => 'Package', 'rules' => 'required|integer'],
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('admin/getkey-links')->withInput()->with('msgDanger', 'Please check the form');
        }

        $linkModel = new GetkeyLinkModel();
        $slug = $linkModel->generateSlug($this->request->getPost('name'));

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $slug,
            'admin_account' => $this->request->getPost('admin_account'),
            'package_id' => $this->request->getPost('package_id'),
            'price_per_hour' => $this->request->getPost('price_per_hour') ?: 0,
            'max_hours' => $this->request->getPost('max_hours') ?: 720,
            'max_devices' => $this->request->getPost('max_devices') ?: 1,
            'youmoney_token' => $this->request->getPost('youmoney_token') ?: null,
            'status' => 1,
            'total_keys_created' => 0,
        ];

        if ($linkModel->insert($data)) {
            return redirect()->to('admin/getkey-links')->with('msgSuccess', 'GetKey link created! Link: ' . base_url('get/' . $slug));
        }

        return redirect()->to('admin/getkey-links')->with('msgDanger', 'Failed to create link');
    }

    public function delete($id)
    {
        if ($this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgWarning', 'Access Denied!');
        }

        $linkModel = new GetkeyLinkModel();
        $linkModel->delete($id);
        return redirect()->back()->with('msgSuccess', 'Link deleted');
    }

    public function toggle($id)
    {
        if ($this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgWarning', 'Access Denied!');
        }

        $linkModel = new GetkeyLinkModel();
        $link = $linkModel->find($id);
        if (!$link) return redirect()->back()->with('msgDanger', 'Link not found');

        $newStatus = $link->status == 1 ? 0 : 1;
        $linkModel->update($id, ['status' => $newStatus]);
        return redirect()->back()->with('msgSuccess', $newStatus == 1 ? 'Link activated' : 'Link deactivated');
    }
}
