<?php

namespace App\Controllers;

use App\Models\ApiTokenModel;
use App\Models\UserModel;
use App\Models\KeysModel;
use App\Models\PackageModel;
use App\Models\HistoryModel;
use CodeIgniter\RESTful\ResourceController;

class ApiKey extends ResourceController
{
    protected $format = 'json';

    /**
     * API endpoint to auto generate key
     * POST /api/generate-key
     *
     * Headers:
     * - Authorization: Bearer {token}
     *
     * Body:
     * - game: package_id (e.g., com.tencent.ig)
     * - duration: hours (e.g., 720)
     * - max_devices: number (e.g., 1)
     */
    public function generateKey()
    {
        // Get token from Authorization header
        $authHeader = $this->request->getHeaderLine('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $this->respond([
                'status' => false,
                'message' => 'Missing or invalid Authorization header'
            ], 401);
        }

        $token = $matches[1];

        // Validate token
        $tokenModel = new ApiTokenModel();
        $apiToken = $tokenModel->getTokenByValue($token);

        if (!$apiToken) {
            return $this->respond([
                'status' => false,
                'message' => 'Invalid API token'
            ], 401);
        }

        // Get admin account from token
        $adminUsername = $apiToken->admin_account;
        $userModel = new UserModel();
        $adminUser = $userModel->where('username', $adminUsername)->first();

        if (!$adminUser) {
            return $this->respond([
                'status' => false,
                'message' => 'Admin account not found'
            ], 500);
        }

        // Get request data
        $game = $this->request->getPost('game');
        $duration = $this->request->getPost('duration');
        $maxDevices = $this->request->getPost('max_devices') ?: 1;

        // Validate input
        if (!$game || !$duration) {
            return $this->respond([
                'status' => false,
                'message' => 'Missing required fields: game, duration'
            ], 400);
        }

        // Validate package exists
        $packageModel = new PackageModel();
        $package = $packageModel->where('package_id', $game)->where('status', 1)->first();

        if (!$package) {
            return $this->respond([
                'status' => false,
                'message' => 'Invalid game package'
            ], 400);
        }

        // Calculate price (example: 1000 per hour per device)
        $pricePerHour = 1000;
        $totalPrice = $duration * $maxDevices * $pricePerHour;

        // Check if admin has enough balance
        if ($adminUser->saldo < $totalPrice) {
            return $this->respond([
                'status' => false,
                'message' => 'Insufficient balance',
                'required' => $totalPrice,
                'current' => $adminUser->saldo
            ], 400);
        }

        // Generate unique key
        $userKey = $this->generateUniqueKey();

        // Create key
        $keysModel = new KeysModel();
        $keyData = [
            'game' => $game,
            'package_id' => $package->id_package,
            'user_key' => $userKey,
            'duration' => $duration,
            'max_devices' => $maxDevices,
            'devices' => null,
            'status' => 1,
            'registrator' => $adminUsername
        ];

        $keyId = $keysModel->insert($keyData, true);

        if (!$keyId) {
            return $this->respond([
                'status' => false,
                'message' => 'Failed to create key'
            ], 500);
        }

        // Deduct balance from admin account
        $newBalance = $adminUser->saldo - $totalPrice;
        $userModel->update($adminUser->id, ['saldo' => $newBalance]);

        // Save to history
        $historyModel = new HistoryModel();
        $historyInfo = implode('|', [
            $game,
            $userKey,
            $duration,
            $maxDevices,
            '' // expired_date will be set when key is first used
        ]);

        $historyModel->insert([
            'keys_id' => $keyId,
            'user_do' => $adminUsername,
            'info' => $historyInfo
        ]);

        // Return success response
        return $this->respond([
            'status' => true,
            'message' => 'Key generated successfully',
            'data' => [
                'key' => $userKey,
                'game' => $game,
                'duration' => $duration . ' hours',
                'max_devices' => $maxDevices,
                'price' => $totalPrice,
                'remaining_balance' => $newBalance
            ]
        ], 201);
    }

    /**
     * Generate unique key
     */
    private function generateUniqueKey()
    {
        $keysModel = new KeysModel();
        do {
            $key = strtoupper(bin2hex(random_bytes(8)));
            $exists = $keysModel->where('user_key', $key)->first();
        } while ($exists);

        return $key;
    }

    /**
     * Check API token balance
     * GET /api/check-balance
     */
    public function checkBalance()
    {
        // Get token from Authorization header
        $authHeader = $this->request->getHeaderLine('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $this->respond([
                'status' => false,
                'message' => 'Missing or invalid Authorization header'
            ], 401);
        }

        $token = $matches[1];

        // Validate token
        $tokenModel = new ApiTokenModel();
        $apiToken = $tokenModel->getTokenByValue($token);

        if (!$apiToken) {
            return $this->respond([
                'status' => false,
                'message' => 'Invalid API token'
            ], 401);
        }

        // Get admin account balance
        $userModel = new UserModel();
        $adminUser = $userModel->where('username', $apiToken->admin_account)->first();

        if (!$adminUser) {
            return $this->respond([
                'status' => false,
                'message' => 'Admin account not found'
            ], 500);
        }

        return $this->respond([
            'status' => true,
            'data' => [
                'admin_account' => $apiToken->admin_account,
                'balance' => $adminUser->saldo
            ]
        ]);
    }
}
