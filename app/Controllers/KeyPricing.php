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

    private function ensureTable()
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `key_pricing` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `duration_hours` int(11) NOT NULL,
            `price` bigint(20) NOT NULL DEFAULT '0',
            `description` varchar(255) DEFAULT NULL,
            `status` tinyint(1) NOT NULL DEFAULT '1',
            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `duration_hours` (`duration_hours`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Insert default data if empty
        $count = $db->table('key_pricing')->countAll();
        if ($count == 0) {
            $db->table('key_pricing')->insertBatch([
                ['duration_hours' => 24, 'price' => 10000, 'description' => '1 ngày', 'status' => 1],
                ['duration_hours' => 168, 'price' => 50000, 'description' => '7 ngày', 'status' => 1],
                ['duration_hours' => 720, 'price' => 150000, 'description' => '30 ngày', 'status' => 1],
            ]);
        }
    }

    public function index()
    {
        if (!$this->user || $this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgDanger', 'Access denied');
        }

        $this->ensureTable();

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
