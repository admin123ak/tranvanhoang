<?php

namespace App\Models;

use CodeIgniter\Model;

class GeneratedKeyModel extends Model
{
    protected $table      = 'generated_keys';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = ['key_code', 'short_url', 'user_key', 'ip_address', 'user_agent'];
    protected $useTimestamps = true;

    public function generateUniqueCode()
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        do {
            $length = 8;
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $chars[rand(0, strlen($chars) - 1)];
            }
            $exists = $this->where('key_code', $code)->first();
        } while ($exists);

        return $code;
    }
}
