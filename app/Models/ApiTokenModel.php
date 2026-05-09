<?php

namespace App\Models;

use CodeIgniter\Model;

class ApiTokenModel extends Model
{
    protected $table      = 'api_tokens';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = ['user_id', 'token', 'name', 'admin_account', 'status'];
    protected $useTimestamps = true;

    public function getTokenByUser($userId)
    {
        return $this->where('user_id', $userId)
            ->where('status', 1)
            ->findAll();
    }

    public function getTokenByValue($token)
    {
        return $this->where('token', $token)
            ->where('status', 1)
            ->first();
    }

    public function generateToken()
    {
        return bin2hex(random_bytes(32));
    }
}
