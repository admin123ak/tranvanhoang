<?php

namespace App\Models;

use CodeIgniter\Model;

class UserPlanModel extends Model
{
    protected $table = 'user_plans';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = ['user_id', 'plan_id', 'packages_used', 'keys_used', 'purchased_at', 'expires_at', 'status'];
    protected $useTimestamps = false;

    public function getUserPlan($userId)
    {
        $builder = $this->db->table('user_plans up');
        $builder->select('up.id, up.user_id, up.plan_id, up.packages_used, up.keys_used, up.purchased_at, up.expires_at, up.status, p.name as plan_name, p.price_per_month, p.max_packages, p.max_keys');
        $builder->join('plans p', 'p.id = up.plan_id', 'left');
        $builder->where('up.user_id', $userId);
        $builder->where('up.status', 1);
        $builder->where('up.expires_at >', date('Y-m-d H:i:s'));
        return $builder->get()->getRow();
    }

    public function getUserPlanHistory($userId)
    {
        $builder = $this->db->table('user_plans up');
        $builder->select('up.id, up.user_id, up.plan_id, up.packages_used, up.keys_used, up.purchased_at, up.expires_at, up.status, p.name as plan_name, p.price_per_month, p.max_packages, p.max_keys');
        $builder->join('plans p', 'p.id = up.plan_id', 'left');
        $builder->where('up.user_id', $userId);
        $builder->orderBy('up.created_at', 'DESC');
        return $builder->get()->getResult();
    }

    public function getPlanStats($userId)
    {
        $plan = $this->getUserPlan($userId);
        if (!$plan) {
            return null;
        }

        // Fallback to default values if columns are missing
        $maxPackages = $plan->max_packages ?? 0;
        $maxKeys = $plan->max_keys ?? 0;
        $packagesUsed = $plan->packages_used ?? 0;
        $keysUsed = $plan->keys_used ?? 0;

        return [
            'plan_id' => $plan->plan_id ?? 0,
            'plan_name' => $plan->plan_name ?? 'Unknown',
            'plan_price' => $plan->price_per_month ?? 0,
            'max_packages' => $maxPackages,
            'packages_used' => $packagesUsed,
            'packages_left' => $maxPackages - $packagesUsed,
            'max_keys' => $maxKeys,
            'keys_used' => $keysUsed,
            'keys_left' => $maxKeys - $keysUsed,
            'expires_at' => $plan->expires_at ?? date('Y-m-d H:i:s'),
        ];
    }

    public function incrementPackagesUsed($userPlanId)
    {
        return $this->db->query(
            "UPDATE user_plans SET packages_used = packages_used + 1 WHERE id = ?",
            [$userPlanId]
        );
    }

    public function incrementKeysUsed($userPlanId)
    {
        return $this->db->query(
            "UPDATE user_plans SET keys_used = keys_used + 1 WHERE id = ?",
            [$userPlanId]
        );
    }
}
