<?php

namespace App\Controllers;

use App\Models\ApiConfigModel;
use App\Models\UserModel;
use App\Models\KeysModel;
use App\Models\HistoryModel;

class GetKey extends BaseController
{
    public function index()
    {
        $configModel = new ApiConfigModel();
        $configs = $configModel->getActiveConfigs();

        $data = [
            'title' => 'Get Key',
            'configs' => $configs,
        ];

        return view('GetKey/index', $data);
    }

    /**
     * Auto create key - no input needed
     * Just click and get key
     */
    public function createKey($configId)
    {
        // Get config
        $configModel = new ApiConfigModel();
        $config = $configModel->find($configId);

        if (!$config || $config->status != 1) {
            return redirect()->back()->with('msgDanger', 'Config not found or inactive');
        }

        // Get admin account
        $userModel = new UserModel();
        $adminUser = $userModel->where('username', $config->admin_account)->first();

        if (!$adminUser) {
            return redirect()->back()->with('msgDanger', 'Admin account not found');
        }

        // Calculate price
        $totalPrice = $config->max_hours * $config->price_per_hour;

        // Check balance
        if ($adminUser->saldo < $totalPrice) {
            return redirect()->back()->with('msgDanger', 'Insufficient admin balance. Required: ' . number_format($totalPrice) . ' VND');
        }

        // Get package info
        $packageModel = new \App\Models\PackageModel();
        $package = $packageModel->find($config->package_id);

        if (!$package) {
            return redirect()->back()->with('msgDanger', 'Package not found');
        }

        $pkgCode = is_object($package) ? ($package->package_id ?? '') : ($package['package_id'] ?? '');
        $pkgName = is_object($package) ? ($package->package_name ?? '') : ($package['package_name'] ?? '');

        // Generate unique key
        $keysModel = new KeysModel();
        $userKey = $this->generateUniqueKey($keysModel);

        // Create key
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

        // Deduct balance
        $newBalance = $adminUser->saldo - $totalPrice;
        $userModel->update($adminUser->id, ['saldo' => $newBalance]);

        // Save history
        $historyModel = new HistoryModel();
        $historyInfo = implode('|', [
            $pkgName,
            $userKey,
            $config->max_hours,
            $config->max_devices,
            '' // expired_date set when key is first used
        ]);

        $historyModel->insert([
            'keys_id' => $keyId,
            'user_do' => $config->admin_account,
            'info' => $historyInfo
        ]);

        // Show success with key
        return redirect()->to('getkey/key-success/' . $userKey);
    }

    /**
     * Display created key
     */
    public function keySuccess($key)
    {
        $keysModel = new KeysModel();
        $keyData = $keysModel->getKeys($key, 'user_key');

        if (!$keyData) {
            return redirect()->to('getkey')->with('msgDanger', 'Key not found');
        }

        $packageModel = new \App\Models\PackageModel();
        $package = $packageModel->find($keyData->package_id);
        $pkgName = $package ? (is_object($package) ? ($package->package_name ?? 'Unknown') : ($package['package_name'] ?? 'Unknown')) : 'Unknown';

        $data = [
            'title' => 'Key Created',
            'key' => $keyData,
            'packageName' => $pkgName,
        ];

        return view('GetKey/success', $data);
    }

    private function generateUniqueKey($keysModel)
    {
        do {
            $key = strtoupper(bin2hex(random_bytes(8)));
            $exists = $keysModel->where('user_key', $key)->first();
        } while ($exists);

        return $key;
    }
}
