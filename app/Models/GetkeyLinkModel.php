<?php

namespace App\Models;

use CodeIgniter\Model;

class GetkeyLinkModel extends Model
{
    protected $table      = 'getkey_links';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = ['admin_account', 'package_id', 'slug', 'name', 'price_per_hour', 'max_hours', 'max_devices', 'youmoney_token', 'status', 'total_keys_created'];
    protected $useTimestamps = true;

    public function getActiveLinks()
    {
        $builder = $this->db->table($this->table);
        $builder->select('getkey_links.*, packages.package_name, packages.package_id as pkg_code');
        $builder->join('packages', 'packages.id_package = getkey_links.package_id', 'left');
        $builder->where('getkey_links.status', 1);
        return $builder->get()->getResultObject();
    }

    public function getLinkBySlug($slug)
    {
        $builder = $this->db->table($this->table);
        $builder->select('getkey_links.*, packages.package_name, packages.package_id as pkg_code');
        $builder->join('packages', 'packages.id_package = getkey_links.package_id', 'left');
        return $builder->where('getkey_links.slug', $slug)
            ->where('getkey_links.status', 1)
            ->get()
            ->getRowObject();
    }

    public function generateSlug($name)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        $originalSlug = $slug;
        $counter = 1;

        while ($this->where('slug', $slug)->first()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
