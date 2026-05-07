<?php

namespace App\Controllers;

use App\Models\PackageModel;
use App\Models\UserModel;
use CodeIgniter\Config\Services;

class Package extends BaseController
{
    protected $model, $userModel, $user;

    public function __construct()
    {
        $this->model = new PackageModel();
        $this->userModel = new UserModel();
        $this->user = $this->userModel->getUser();
    }

    public function index()
    {
        $user = $this->user;
        if ($user->level != 1) {
            return redirect()->to('dashboard')->with('msgWarning', 'Access Denied!');
        }

        $data = [
            'title' => 'Packages',
            'user' => $user,
            'packages' => $this->model->findAll(),
            'validation' => Services::validation()
        ];
        return view('Admin/packages', $data);
    }

    public function create()
    {
        $user = $this->user;
        if ($user->level != 1) {
            return redirect()->to('dashboard')->with('msgWarning', 'Access Denied!');
        }

        if ($this->request->getPost()) {
            return $this->create_action();
        }

        $data = [
            'title' => 'Create Package',
            'user' => $user,
            'validation' => Services::validation()
        ];
        return view('Admin/package_create', $data);
    }

    private function create_action()
    {
        $form_rules = [
            'package_name' => [
                'label' => 'package name',
                'rules' => 'required|min_length[3]|max_length[100]',
            ],
            'package_id' => [
                'label' => 'package ID',
                'rules' => 'required|min_length[3]|max_length[255]|is_unique[packages.package_id]',
                'errors' => [
                    'is_unique' => 'This {field} already exists.'
                ]
            ],
            'description' => [
                'label' => 'description',
                'rules' => 'permit_empty|max_length[500]',
            ],
            'status' => [
                'label' => 'status',
                'rules' => 'required|in_list[0,1]',
            ]
        ];

        if (!$this->validate($form_rules)) {
            return redirect()->back()->withInput()->with('msgDanger', 'Failed! Please check the form.');
        }

        $data = [
            'package_name' => $this->request->getPost('package_name'),
            'package_id' => $this->request->getPost('package_id'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status'),
        ];

        $id = $this->model->insert($data);
        if ($id) {
            return redirect()->to('admin/packages')->with('msgSuccess', 'Package created successfully!');
        }

        return redirect()->back()->withInput()->with('msgDanger', 'Failed to create package.');
    }

    public function edit($id = null)
    {
        $user = $this->user;
        if ($user->level != 1) {
            return redirect()->to('dashboard')->with('msgWarning', 'Access Denied!');
        }

        $package = $this->model->find($id);
        if (!$package) {
            return redirect()->to('admin/packages')->with('msgDanger', 'Package not found.');
        }

        if ($this->request->getPost()) {
            return $this->edit_action($id);
        }

        $data = [
            'title' => 'Edit Package',
            'user' => $user,
            'package' => $package,
            'validation' => Services::validation()
        ];
        return view('Admin/package_edit', $data);
    }

    private function edit_action($id)
    {
        $package = $this->model->find($id);
        if (!$package) {
            return redirect()->to('admin/packages')->with('msgDanger', 'Package not found.');
        }

        $form_rules = [
            'package_name' => [
                'label' => 'package name',
                'rules' => 'required|min_length[3]|max_length[100]',
            ],
            'package_id' => [
                'label' => 'package ID',
                'rules' => "required|min_length[3]|max_length[255]|is_unique[packages.package_id,id_package,$id]",
                'errors' => [
                    'is_unique' => 'This {field} already exists.'
                ]
            ],
            'description' => [
                'label' => 'description',
                'rules' => 'permit_empty|max_length[500]',
            ],
            'status' => [
                'label' => 'status',
                'rules' => 'required|in_list[0,1]',
            ]
        ];

        if (!$this->validate($form_rules)) {
            return redirect()->back()->withInput()->with('msgDanger', 'Failed! Please check the form.');
        }

        $data = [
            'package_name' => $this->request->getPost('package_name'),
            'package_id' => $this->request->getPost('package_id'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status'),
        ];

        $updated = $this->model->update($id, $data);
        if ($updated) {
            return redirect()->to('admin/packages')->with('msgSuccess', 'Package updated successfully!');
        }

        return redirect()->back()->withInput()->with('msgDanger', 'Failed to update package.');
    }

    public function delete($id = null)
    {
        $user = $this->user;
        if ($user->level != 1) {
            return redirect()->to('dashboard')->with('msgWarning', 'Access Denied!');
        }

        $package = $this->model->find($id);
        if (!$package) {
            return redirect()->to('admin/packages')->with('msgDanger', 'Package not found.');
        }

        $deleted = $this->model->delete($id);
        if ($deleted) {
            return redirect()->to('admin/packages')->with('msgSuccess', 'Package deleted successfully!');
        }

        return redirect()->to('admin/packages')->with('msgDanger', 'Failed to delete package.');
    }
}
