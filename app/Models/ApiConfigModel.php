<?php

namespace App\Models;

use CodeIgniter\Model;

class ApiConfigModel extends Model
{
    protected $table      = 'api_config';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = ['admin_account', 'package_id', 'price_per_hour', 'min_hours', 'max_hours', 'max_devices', 'status'];
    protected $useTimestamps = true;

    public function getActiveConfigs()
    {
        $builder = $this->db->table($this->table);
        $builder->select('api_config.*, packages.package_name, packages.package_id as pkg_code');
        $builder->join('packages', 'packages.id_package = api_config.package_id', 'left');
        $builder->where('api_config.status', 1);
        return $builder->get()->getResultObject();
    }

    public function getConfigByPackage($packageId)
    {
        return $this->where('package_id', $packageId)
            ->where('status', 1)
            ->first();
    }
}
