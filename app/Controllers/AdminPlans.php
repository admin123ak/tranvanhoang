<?php

namespace App\Controllers;

use App\Models\PlanModel;

class AdminPlans extends BaseController
{
    public function index()
    {
        $planModel = new PlanModel();
        $plans = $planModel->findAll();

        // Count active subscriptions
        $db = \Config\Database::connect();
        try {
            $activeSubs = $db->query("SELECT COUNT(*) as cnt FROM user_plans WHERE status = 1 AND expires_at > NOW()")->getRow();
            $activeSubscriptions = $activeSubs->cnt ?? 0;
        } catch (\Exception $e) {
            $activeSubscriptions = 0;
        }

        $data = [
            'title' => 'Quan ly Goi',
            'plans' => $plans,
            'activeSubscriptions' => $activeSubscriptions,
        ];

        return view('Admin/plans', $data);
    }

    public function create()
    {
        $planModel = new PlanModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'price_per_month' => (int) $this->request->getPost('price_per_month'),
            'max_packages' => (int) $this->request->getPost('max_packages'),
            'max_keys' => (int) $this->request->getPost('max_keys'),
            'status' => (int) $this->request->getPost('status'),
            'description' => $this->request->getPost('description'),
        ];

        if ($planModel->insert($data)) {
            return redirect()->to('admin/plans')->with('msgSuccess', 'Them goi thanh cong');
        }

        return redirect()->back()->with('msgDanger', 'Them goi that bai');
    }

    public function edit($id)
    {
        $planModel = new PlanModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'price_per_month' => (int) $this->request->getPost('price_per_month'),
            'max_packages' => (int) $this->request->getPost('max_packages'),
            'max_keys' => (int) $this->request->getPost('max_keys'),
            'status' => (int) $this->request->getPost('status'),
            'description' => $this->request->getPost('description'),
        ];

        if ($planModel->update($id, $data)) {
            return redirect()->to('admin/plans')->with('msgSuccess', 'Sua goi thanh cong');
        }

        return redirect()->back()->with('msgDanger', 'Sua goi that bai');
    }

    public function delete($id)
    {
        $planModel = new PlanModel();
        if ($planModel->delete($id)) {
            return redirect()->to('admin/plans')->with('msgSuccess', 'Xoa goi thanh cong');
        }
        return redirect()->back()->with('msgDanger', 'Xoa goi that bai');
    }
}
