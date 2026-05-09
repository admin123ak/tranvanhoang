<?php

namespace App\Models;

use CodeIgniter\Model;

class GetkeyConfigModel extends Model
{
    protected $table      = 'getkey_config';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = ['admin_account', 'package_id', 'price_per_hour', 'max_hours', 'max_devices', 'youmoney_token', 'status'];
    protected $useTimestamps = true;

    public function getActiveConfig()
    {
        $builder = $this->db->table($this->table);
        $builder->select('getkey_config.*, packages.package_name, packages.package_id as pkg_code');
        $builder->join('packages', 'packages.id_package = getkey_config.package_id', 'left');
        $builder->where('getkey_config.status', 1);
        return $builder->get()->getRowObject();
    }
}
