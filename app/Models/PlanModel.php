<?php

namespace App\Models;

use CodeIgniter\Model;

class PlanModel extends Model
{
    protected $table = 'plans';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = ['name', 'price_per_month', 'max_packages', 'max_keys', 'status', 'description'];
    protected $useTimestamps = false;

    public function getActivePlans()
    {
        return $this->where('status', 1)->orderBy('price_per_month', 'ASC')->findAll();
    }
}
