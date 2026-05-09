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
        $action = $this->request->getPost('action'); // 'new', 'upgrade', 'renew'
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

        $userPlanModel = new UserPlanModel();
        $existingPlan = $userPlanModel->getUserPlan($this->user->id_users);

        // If no action specified, determine from context
        if (!$action) {
            if (!$existingPlan) {
                $action = 'new';
            } elseif ($plan->price_per_month > $existingPlan->price_per_month) {
                $action = 'upgrade';
            } else {
                $action = 'new'; // default to new (will be blocked below)
            }
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

        // Block downgrade
        if ($existingPlan && $action !== 'renew') {
            if ($plan->price_per_month <= $existingPlan->price_per_month && $plan->id != $existingPlan->plan_id) {
                return redirect()->back()->with('msgDanger', 'Khong the downgrade. Ban dang co goi ' . $existingPlan->plan_name . ', chi duoc nang cap hoac gia han.');
            }
        }

        // If buying same plan while having no active plan (expired) -> treat as renew
        if (!$existingPlan && $action === 'upgrade') {
            $action = 'new';
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Deduct saldo
            $newSaldo = $user->saldo - $totalPrice;
            $this->userModel->update($user->id_users, ['saldo' => $newSaldo]);

            if ($action === 'renew' && $existingPlan) {
                // Renew: extend from current expires_at or from now
                $baseDate = strtotime($existingPlan->expires_at) > time() ? $existingPlan->expires_at : date('Y-m-d H:i:s');
                $expiresAt = date('Y-m-d H:i:s', strtotime("+{$durationDays} days", strtotime($baseDate)));

                $userPlanModel->update($existingPlan->id, [
                    'plan_id' => $plan->id,
                    'packages_used' => 0,
                    'keys_used' => 0,
                    'purchased_at' => date('Y-m-d H:i:s'),
                    'expires_at' => $expiresAt,
                    'status' => 1,
                ]);

                $historyModel = new HistoryModel();
                $historyModel->insert([
                    'user_do' => $user->username,
                    'info' => "Gia han goi {$plan->name} | {$durationDays} ngay | Gia: " . number_format($totalPrice, 0, ',', '.') . 'd | Het han: ' . $expiresAt
                ]);
            } elseif ($action === 'upgrade' && $existingPlan) {
                // Upgrade: reset counters, extend from current expires_at
                $baseDate = strtotime($existingPlan->expires_at) > time() ? $existingPlan->expires_at : date('Y-m-d H:i:s');
                $expiresAt = date('Y-m-d H:i:s', strtotime("+{$durationDays} days", strtotime($baseDate)));

                $userPlanModel->update($existingPlan->id, [
                    'plan_id' => $plan->id,
                    'packages_used' => 0,
                    'keys_used' => 0,
                    'purchased_at' => date('Y-m-d H:i:s'),
                    'expires_at' => $expiresAt,
                    'status' => 1,
                ]);

                $historyModel = new HistoryModel();
                $historyModel->insert([
                    'user_do' => $user->username,
                    'info' => "Nang cap tu {$existingPlan->plan_name} len {$plan->name} | {$durationDays} ngay | Gia: " . number_format($totalPrice, 0, ',', '.') . 'd | Het han: ' . $expiresAt
                ]);
            } else {
                // New purchase
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

                $historyModel = new HistoryModel();
                $historyModel->insert([
                    'user_do' => $user->username,
                    'info' => "Mua goi {$plan->name} | {$durationDays} ngay | Gia: " . number_format($totalPrice, 0, ',', '.') . 'd | Het han: ' . $expiresAt
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                log_message('error', 'Transaction failed for plan purchase');
                return redirect()->back()->with('msgDanger', 'Transaction that bai - kiem tra lai database');
            }

            $actionText = ($action === 'upgrade') ? 'Nang cap' : (($action === 'renew') ? 'Gia han' : 'Da mua');
            return redirect()->to('plans')->with('msgSuccess', $actionText . ' goi ' . $plan->name . ' (' . $durationDays . ' ngay) thanh cong!');
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Plan purchase failed: ' . $e->getMessage());
            return redirect()->back()->with('msgDanger', 'Loi: ' . $e->getMessage());
        }
    }

    public function renew()
    {
        $durationDays = (int) $this->request->getPost('duration_days');
        if (!in_array($durationDays, [30, 90, 365])) {
            $durationDays = 30;
        }

        $userPlanModel = new UserPlanModel();
        $existingPlan = $userPlanModel->getUserPlan($this->user->id_users);

        if (!$existingPlan) {
            return redirect()->to('plans')->with('msgDanger', 'Ban khong co goi hoat dong');
        }

        // Calculate price based on duration
        if ($durationDays === 30) {
            $totalPrice = $existingPlan->price_per_month;
        } elseif ($durationDays === 90) {
            $totalPrice = $existingPlan->price_per_month * 3;
        } else {
            $totalPrice = $existingPlan->price_per_month * 12;
        }

        $user = $this->user;
        if ($user->saldo < $totalPrice) {
            return redirect()->back()->with('msgDanger', 'So du khong du. Can ' . number_format($totalPrice - $user->saldo, 0, ',', '.') . 'd nua');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Deduct saldo
            $newSaldo = $user->saldo - $totalPrice;
            $this->userModel->update($user->id_users, ['saldo' => $newSaldo]);

            // Extend from current expires_at
            $baseDate = strtotime($existingPlan->expires_at) > time() ? $existingPlan->expires_at : date('Y-m-d H:i:s');
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$durationDays} days", strtotime($baseDate)));

            $userPlanModel->update($existingPlan->id, [
                'packages_used' => 0,
                'keys_used' => 0,
                'purchased_at' => date('Y-m-d H:i:s'),
                'expires_at' => $expiresAt,
                'status' => 1,
            ]);

            $historyModel = new HistoryModel();
            $historyModel->insert([
                'user_do' => $user->username,
                'info' => "Gia han goi {$existingPlan->plan_name} | {$durationDays} ngay | Gia: " . number_format($totalPrice, 0, ',', '.') . 'd | Het han: ' . $expiresAt
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('msgDanger', 'Transaction that bai');
            }

            return redirect()->to('plans')->with('msgSuccess', 'Gia han goi ' . $existingPlan->plan_name . ' thanh cong! Het han: ' . date('d/m/Y', strtotime($expiresAt)));
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Plan renew failed: ' . $e->getMessage());
            return redirect()->back()->with('msgDanger', 'Loi: ' . $e->getMessage());
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
