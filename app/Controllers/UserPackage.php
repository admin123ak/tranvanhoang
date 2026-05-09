<?php

namespace App\Controllers;

use App\Models\PackageModel;
use App\Models\UserModel;
use App\Models\UserPlanModel;
use CodeIgniter\Config\Services;

class UserPackage extends BaseController
{
    protected $userModel;
    protected $user;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->user = $this->userModel->getUser();
    }

    public function index()
    {
        $user = $this->user;

        // Admin can always create packages
        $isAdmin = ($user->level == 1);

        $userPlanModel = new UserPlanModel();
        $planStats = $userPlanModel->getPlanStats($user->id_users);

        $packageModel = new PackageModel();
        $packages = $packageModel->findAll();

        $data = [
            'title' => 'Package cua toi',
            'user' => $user,
            'packages' => $packages,
            'isAdmin' => $isAdmin,
            'planStats' => $planStats,
            'canCreate' => $isAdmin || ($planStats !== null && $planStats['packages_left'] > 0),
        ];

        return view('User/packages', $data);
    }

    public function create()
    {
        $user = $this->user;
        $isAdmin = ($user->level == 1);

        if (!$isAdmin) {
            $userPlanModel = new UserPlanModel();
            $planStats = $userPlanModel->getPlanStats($user->id_users);
            if (!$planStats || $planStats['packages_left'] <= 0) {
                return redirect()->to('plans')->with('msgDanger', 'Ban khong con quota de tao package. Vui long mua goi moi.');
            }
        }

        $data = [
            'title' => 'Tao Package',
            'user' => $user,
            'validation' => Services::validation(),
        ];

        return view('User/package_create', $data);
    }

    public function create_action()
    {
        $user = $this->user;
        $isAdmin = ($user->level == 1);

        if (!$isAdmin) {
            $userPlanModel = new UserPlanModel();
            $planStats = $userPlanModel->getPlanStats($user->id_users);
            if (!$planStats || $planStats['packages_left'] <= 0) {
                return redirect()->to('plans')->with('msgDanger', 'Ban khong con quota de tao package.');
            }
        }

        $form_rules = [
            'package_name' => [
                'label' => 'Ten package',
                'rules' => 'required|min_length[3]|max_length[100]',
            ],
            'package_id' => [
                'label' => 'Package ID',
                'rules' => 'required|min_length[3]|max_length[255]|is_unique[packages.package_id]',
                'errors' => [
                    'is_unique' => 'Package ID nay da ton tai.'
                ]
            ],
            'description' => [
                'label' => 'Mo ta',
                'rules' => 'permit_empty|max_length[500]',
            ],
        ];

        $validation = Services::validation();
        if (!$validation->setRules($form_rules)->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('msgDanger', 'Failed! Please check the form.');
        }

        $packageModel = new PackageModel();
        $data = [
            'package_name' => $this->request->getPost('package_name'),
            'package_id' => $this->request->getPost('package_id'),
            'description' => $this->request->getPost('description'),
            'status' => 1,
        ];

        $id = $packageModel->insert($data);
        if ($id) {
            // Increment package usage if not admin
            if (!$isAdmin) {
                $userPlanModel = new UserPlanModel();
                $planStats = $userPlanModel->getPlanStats($user->id_users);
                $activePlan = $userPlanModel->getUserPlan($user->id_users);
                if ($activePlan) {
                    $userPlanModel->incrementPackagesUsed($activePlan->id);
                }
            }
            return redirect()->to('user/packages')->with('msgSuccess', 'Package tao thanh cong!');
        }

        return redirect()->back()->withInput()->with('msgDanger', 'Failed to create package.');
    }
}
