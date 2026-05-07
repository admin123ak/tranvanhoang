<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageModel extends Model
{
    protected $table      = 'packages';
    protected $primaryKey = 'id_package';
    protected $returnType = 'object';
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
            // Check if table exists first
            $db = db_connect();
            $tableExists = $db->tableExists($this->table);
            if (!$tableExists) {
                return [];
            }
            return $this->where('status', 1)->findAll();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getPackageByPackageId($package_id)
    {
        try {
            return $this->where('package_id', $package_id)
                ->where('status', 1)
                ->get()
                ->getRowObject();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getAllPackagesList()
    {
        try {
            $packages = $this->findAll();
            $list = [];
            foreach ($packages as $pkg) {
                $list[$pkg->id_package] = $pkg->package_name;
            }
            return $list;
        } catch (\Exception $e) {
            return [];
        }
    }
}
