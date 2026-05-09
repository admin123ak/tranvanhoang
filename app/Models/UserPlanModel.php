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
        $builder->select('up.*, p.name as plan_name, p.price_per_month, p.max_packages, p.max_keys');
        $builder->join('plans p', 'p.id = up.plan_id', 'left');
        $builder->where('up.user_id', $userId);
        $builder->where('up.status', 1);
        $builder->where('up.expires_at >', date('Y-m-d H:i:s'));
        return $builder->get()->getRow();
    }

    public function getUserPlanHistory($userId)
    {
        $builder = $this->db->table('user_plans up');
        $builder->select('up.*, p.name as plan_name, p.price_per_month, p.max_packages, p.max_keys');
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

        return [
            'plan_name' => $plan->plan_name,
            'plan_price' => $plan->price_per_month,
            'max_packages' => $plan->max_packages,
            'packages_used' => $plan->packages_used,
            'packages_left' => $plan->max_packages - $plan->packages_used,
            'max_keys' => $plan->max_keys,
            'keys_used' => $plan->keys_used,
            'keys_left' => $plan->max_keys - $plan->keys_used,
            'expires_at' => $plan->expires_at,
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
