<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageModel extends Model
{
    protected $table      = 'packages';
    protected $primaryKey = 'id_package';
    protected $allowedFields = ['package_name', 'package_id', 'description', 'status'];
    protected $useTimestamps = true;

    public function getPackage($id = false, $where = 'id_package')
    {
        if ($id === false) {
            return $this->where('status', 1)->findAll();
        }

        return $this->where($where, $id)
            ->get()
            ->getRowObject();
    }

    public function getActivePackages()
    {
        try {
            return $this->where('status', 1)
                ->findAll();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getPackageByPackageId($package_id)
    {
        return $this->where('package_id', $package_id)
            ->where('status', 1)
            ->get()
            ->getRowObject();
    }
}
