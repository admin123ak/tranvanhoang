<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Config\Services;

class KeyPricing extends BaseController
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
        $builder = $db->table('key_pricing');
        $pricings = $builder->orderBy('duration_hours', 'ASC')->get()->getResult();

        $data = [
            'title' => 'Key Pricing Management',
            'user' => $this->user,
            'pricings' => $pricings,
            'validation' => Services::validation(),
        ];

        return view('Admin/key_pricing', $data);
    }

    public function create()
    {
        if (!$this->user || $this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgDanger', 'Access denied');
        }

        $rules = [
            'duration_hours' => 'required|numeric|greater_than[0]',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'description' => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('msgDanger', 'Invalid input');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('key_pricing');

        // Check if duration already exists
        $existing = $builder->where('duration_hours', $this->request->getPost('duration_hours'))->get()->getRow();
        if ($existing) {
            return redirect()->back()->with('msgDanger', 'Duration already exists');
        }

        $data = [
            'duration_hours' => $this->request->getPost('duration_hours'),
            'price' => $this->request->getPost('price'),
            'description' => $this->request->getPost('description'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($builder->insert($data)) {
            return redirect()->to('admin/key-pricing')->with('msgSuccess', 'Pricing added successfully');
        }

        return redirect()->back()->with('msgDanger', 'Failed to add pricing');
    }

    public function edit($id)
    {
        if (!$this->user || $this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgDanger', 'Access denied');
        }

        $rules = [
            'duration_hours' => 'required|numeric|greater_than[0]',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'description' => 'permit_empty|max_length[255]',
            'status' => 'required|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('msgDanger', 'Invalid input');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('key_pricing');

        // Check if duration already exists (excluding current record)
        $existing = $builder->where('duration_hours', $this->request->getPost('duration_hours'))
            ->where('id !=', $id)
            ->get()->getRow();
        if ($existing) {
            return redirect()->back()->with('msgDanger', 'Duration already exists');
        }

        $data = [
            'duration_hours' => $this->request->getPost('duration_hours'),
            'price' => $this->request->getPost('price'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($builder->where('id', $id)->update($data)) {
            return redirect()->to('admin/key-pricing')->with('msgSuccess', 'Pricing updated successfully');
        }

        return redirect()->back()->with('msgDanger', 'Failed to update pricing');
    }

    public function delete($id)
    {
        if (!$this->user || $this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgDanger', 'Access denied');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('key_pricing');

        if ($builder->where('id', $id)->delete()) {
            return redirect()->to('admin/key-pricing')->with('msgSuccess', 'Pricing deleted successfully');
        }

        return redirect()->back()->with('msgDanger', 'Failed to delete pricing');
    }
}
