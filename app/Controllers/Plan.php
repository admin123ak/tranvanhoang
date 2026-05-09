<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PlanModel;
use App\Models\UserPlanModel;
use App\Models\HistoryModel;

class Plan extends BaseController
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
        $planModel = new PlanModel();
        $plans = $planModel->getActivePlans();

        $currentPlan = null;
        if ($this->user->level != 1) {
            $userPlanModel = new UserPlanModel();
            $currentPlan = $userPlanModel->getPlanStats($this->user->id_users);
        }

        $data = [
            'title' => 'Goi Thanh Vien',
            'user' => $this->user,
            'plans' => $plans,
            'currentPlan' => $currentPlan,
        ];

        return view('User/plans', $data);
    }

    public function purchase()
    {
        $planId = $this->request->getPost('plan_id');
        $durationDays = (int) $this->request->getPost('duration_days');

        if (!in_array($durationDays, [30, 90, 365])) {
            $durationDays = 30;
        }

        if (!$planId) {
            return redirect()->back()->with('msgDanger', 'Vui long chon goi');
        }

        $planModel = new PlanModel();
        $plan = $planModel->find($planId);

        if (!$plan || $plan->status != 1) {
            return redirect()->back()->with('msgDanger', 'Goi khong ton tai');
        }

        // Calculate price
        if ($durationDays === 30) {
            $totalPrice = $plan->price_per_month;
        } elseif ($durationDays === 90) {
            $totalPrice = $plan->price_per_month * 3;
        } else {
            $totalPrice = $plan->price_per_month * 12;
        }

        $user = $this->user;
        if ($user->saldo < $totalPrice) {
            return redirect()->back()->with('msgDanger', 'So du khong du. Can ' . number_format($totalPrice - $user->saldo, 0, ',', '.') . 'd nua');
        }

        $userPlanModel = new UserPlanModel();
        $existingPlan = $userPlanModel->getUserPlan($user->id_users);
        if ($existingPlan) {
            return redirect()->back()->with('msgDanger', 'Ban dang co goi ' . $existingPlan->plan_name . ' con han.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Deduct saldo
            $newSaldo = $user->saldo - $totalPrice;
            $this->userModel->update($user->id_users, ['saldo' => $newSaldo]);

            // Create user plan
            $purchasedAt = date('Y-m-d H:i:s');
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$durationDays} days"));

            $userPlanModel->insert([
                'user_id' => $user->id_users,
                'plan_id' => $plan->id,
                'packages_used' => 0,
                'keys_used' => 0,
                'purchased_at' => $purchasedAt,
                'expires_at' => $expiresAt,
                'status' => 1,
            ]);

            // Log history
            $historyModel = new HistoryModel();
            $historyModel->insert([
                'user_do' => $user->username,
                'info' => "Mua goi {$plan->name} | {$durationDays} ngay | Gia: " . number_format($totalPrice, 0, ',', '.') . 'd | Het han: ' . $expiresAt
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('msgDanger', 'Co loi xay ra khi mua goi');
            }

            return redirect()->to('plans')->with('msgSuccess', 'Da mua goi ' . $plan->name . ' (' . $durationDays . ' ngay) thanh cong!');
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Plan purchase failed: ' . $e->getMessage());
            return redirect()->back()->with('msgDanger', 'Co loi xay ra khi mua goi');
        }
    }

    public function myPlan()
    {
        $userPlanModel = new UserPlanModel();
        $stats = $userPlanModel->getPlanStats($this->user->id_users);
        $history = $userPlanModel->getUserPlanHistory($this->user->id_users);

        $data = [
            'title' => 'Goi cua toi',
            'user' => $this->user,
            'currentPlan' => $stats,
            'planHistory' => $history,
        ];

        return view('User/my_plan', $data);
    }
}
