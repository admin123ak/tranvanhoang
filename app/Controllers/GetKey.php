<?php

namespace App\Controllers;

use App\Models\GetkeyConfigModel;
use App\Models\GeneratedKeyModel;
use App\Models\UserModel;
use App\Models\KeysModel;
use App\Models\HistoryModel;
use App\Models\PackageModel;

class GetKey extends BaseController
{
    /**
     * Public page: /getkey
     * User clicks "Get Link" - system auto generates key + unique link
     */
    public function index()
    {
        $configModel = new GetkeyConfigModel();
        $config = $configModel->getActiveConfig();

        if (!$config) {
            return view('GetKey/unavailable', ['title' => 'GetKey Unavailable']);
        }

        $data = [
            'title' => 'Get Key',
            'config' => $config,
        ];

        return view('GetKey/index', $data);
    }

    /**
     * POST: /getkey/generate
     * Auto create key + unique link for user
     */
    public function generate()
    {
        $configModel = new GetkeyConfigModel();
        $config = $configModel->getActiveConfig();

        if (!$config || $config->status != 1) {
            return redirect()->back()->with('msgDanger', 'GetKey service is currently unavailable');
        }

        // Get admin account
        $userModel = new UserModel();
        $adminUser = $userModel->where('username', $config->admin_account)->first();

        if (!$adminUser) {
            return redirect()->back()->with('msgDanger', 'Admin account not found');
        }

        // Calculate price (free if price_per_hour = 0)
        $totalPrice = $config->max_hours * $config->price_per_hour;

        // Check balance (skip if free)
        if ($totalPrice > 0 && $adminUser->saldo < $totalPrice) {
            return redirect()->back()->with('msgDanger', 'Service temporarily unavailable (insufficient balance)');
        }

        // Get package info
        $packageModel = new PackageModel();
        $package = $packageModel->find($config->package_id);

        if (!$package) {
            return redirect()->back()->with('msgDanger', 'Package not found');
        }

        $pkgCode = is_object($package) ? ($package->package_id ?? '') : ($package['package_id'] ?? '');
        $pkgName = is_object($package) ? ($package->package_name ?? '') : ($package['package_name'] ?? '');

        // Generate unique key (format: admin_username_XXXXX)
        $keysModel = new KeysModel();
        $userKey = $this->generateUniqueKey($keysModel, $config->admin_account);

        // Create key in keys_code table
        $keyData = [
            'game' => $pkgCode,
            'package_id' => $config->package_id,
            'user_key' => $userKey,
            'duration' => $config->max_hours,
            'max_devices' => $config->max_devices,
            'devices' => null,
            'status' => 1,
            'registrator' => $config->admin_account
        ];

        $keyId = $keysModel->insert($keyData, true);

        if (!$keyId) {
            return redirect()->back()->with('msgDanger', 'Failed to create key');
        }

        // Generate unique code for link
        $generatedKeyModel = new GeneratedKeyModel();
        $keyCode = $generatedKeyModel->generateUniqueCode();

        // Generate full URL
        $fullUrl = base_url('key/' . $keyCode);
        $shortUrl = null;

        // If YeuMoney token is provided, shorten the URL
        if ($config->youmoney_token) {
            $shortUrl = $this->shortenViaYeuMoney($config->youmoney_token, $fullUrl);
        }

        // Save generated key record
        $generatedKeyModel->insert([
            'key_code' => $keyCode,
            'short_url' => $shortUrl,
            'user_key' => $userKey,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ]);

        // Deduct balance (if not free)
        if ($totalPrice > 0) {
            $newBalance = $adminUser->saldo - $totalPrice;
            $userModel->update($adminUser->id_users, ['saldo' => $newBalance]);
        }

        // Save history
        $historyModel = new HistoryModel();
        $historyInfo = implode('|', [
            $pkgName,
            $userKey,
            $config->max_hours,
            $config->max_devices,
            ''
        ]);

        $historyModel->insert([
            'keys_id' => $keyId,
            'user_do' => $config->admin_account,
            'info' => $historyInfo
        ]);

        // Show success page with link
        $data = [
            'title' => 'Link Generated',
            'keyCode' => $keyCode,
            'fullUrl' => $fullUrl,
            'shortUrl' => $shortUrl,
            'userKey' => $userKey,
            'config' => $config,
            'packageName' => $pkgName,
        ];

        return view('GetKey/success', $data);
    }

    /**
     * Public page: /key/{code}
     * Display key details
     */
    public function show($code)
    {
        $generatedKeyModel = new GeneratedKeyModel();
        $generated = $generatedKeyModel->where('key_code', $code)->first();

        if (!$generated) {
            return view('GetKey/notfound', ['title' => 'Key Not Found']);
        }

        $keysModel = new KeysModel();
        $key = $keysModel->where('user_key', $generated->user_key)->first();

        if (!$key) {
            return view('GetKey/notfound', ['title' => 'Key Not Found']);
        }

        $packageModel = new PackageModel();
        $package = $packageModel->find($key->package_id);
        $pkgName = is_object($package) ? ($package->package_name ?? 'Unknown') : ($package['package_name'] ?? 'Unknown');

        $data = [
            'title' => 'Your Key',
            'key' => $key,
            'packageName' => $pkgName,
        ];

        return view('GetKey/show', $data);
    }

    private function generateUniqueKey($keysModel, $adminUsername)
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        do {
            $length = rand(5, 6);
            $random = '';
            for ($i = 0; $i < $length; $i++) {
                $random .= $chars[rand(0, strlen($chars) - 1)];
            }
            $key = $adminUsername . '_' . $random;
            $exists = $keysModel->where('user_key', $key)->first();
        } while ($exists);

        return $key;
    }

    private function shortenViaYeuMoney($token, $url)
    {
        $apiUrl = "https://yeumoney.com/QL_api.php?token=" . urlencode($token) . "&url=" . urlencode($url) . "&format=json";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            return null;
        }

        $result = json_decode($response, true);

        if ($result && isset($result['status']) && $result['status'] === 'success') {
            return $result['shortenedUrl'];
        }

        return null;
    }
}
