<?php

namespace App\Controllers;

use App\Models\GetkeyLinkModel;
use App\Models\UserModel;
use App\Models\KeysModel;
use App\Models\HistoryModel;
use App\Models\PackageModel;

class GetKey extends BaseController
{
    /**
     * Public page: /get/{slug}
     * User just clicks "Get Key" - no input needed
     */
    public function index($slug)
    {
        $linkModel = new GetkeyLinkModel();
        $link = $linkModel->getLinkBySlug($slug);

        if (!$link) {
            return redirect()->to('login')->with('msgDanger', 'Link not found or inactive');
        }

        $data = [
            'title' => 'Get Key',
            'link' => $link,
        ];

        return view('GetKey/index', $data);
    }

    /**
     * POST: /getkey/create/{slug}
     * Auto create key - no user input needed
     */
    public function createKey($slug)
    {
        $linkModel = new GetkeyLinkModel();
        $link = $linkModel->getLinkBySlug($slug);

        if (!$link || $link->status != 1) {
            return redirect()->back()->with('msgDanger', 'Link not found or inactive');
        }

        // Get admin account
        $userModel = new UserModel();
        $adminUser = $userModel->where('username', $link->admin_account)->first();

        if (!$adminUser) {
            return redirect()->back()->with('msgDanger', 'Admin account not found');
        }

        // Calculate price (free if price_per_hour = 0)
        $totalPrice = $link->max_hours * $link->price_per_hour;

        // Check balance (skip if free)
        if ($totalPrice > 0 && $adminUser->saldo < $totalPrice) {
            return redirect()->back()->with('msgDanger', 'Insufficient admin balance');
        }

        // Get package info
        $packageModel = new PackageModel();
        $package = $packageModel->find($link->package_id);

        if (!$package) {
            return redirect()->back()->with('msgDanger', 'Package not found');
        }

        $pkgCode = is_object($package) ? ($package->package_id ?? '') : ($package['package_id'] ?? '');
        $pkgName = is_object($package) ? ($package->package_name ?? '') : ($package['package_name'] ?? '');

        // Generate unique key (format: admin_username_XXXXX)
        $keysModel = new KeysModel();
        $userKey = $this->generateUniqueKey($keysModel, $link->admin_account);

        // Create key
        $keyData = [
            'game' => $pkgCode,
            'package_id' => $link->package_id,
            'user_key' => $userKey,
            'duration' => $link->max_hours,
            'max_devices' => $link->max_devices,
            'devices' => null,
            'status' => 1,
            'registrator' => $link->admin_account
        ];

        $keyId = $keysModel->insert($keyData, true);

        if (!$keyId) {
            return redirect()->back()->with('msgDanger', 'Failed to create key');
        }

        // Deduct balance (if not free)
        if ($totalPrice > 0) {
            $newBalance = $adminUser->saldo - $totalPrice;
            $userModel->update($adminUser->id, ['saldo' => $newBalance]);
        }

        // Update total keys created
        $linkModel->update($link->id, ['total_keys_created' => $link->total_keys_created + 1]);

        // Save history
        $historyModel = new HistoryModel();
        $historyInfo = implode('|', [
            $pkgName,
            $userKey,
            $link->max_hours,
            $link->max_devices,
            ''
        ]);

        $historyModel->insert([
            'keys_id' => $keyId,
            'user_do' => $link->admin_account,
            'info' => $historyInfo
        ]);

        // Show success page
        $data = [
            'title' => 'Key Created',
            'key' => $keyData,
            'packageName' => $pkgName,
            'link' => $link,
        ];

        return view('GetKey/success', $data);
    }

    private function generateUniqueKey($keysModel, $adminUsername)
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        do {
            // Generate 5-6 random characters
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
}
