<?php

namespace App\Models;

use Exception;

class UserDevice
{
    protected $db;

    public function __construct()
    {
        $this->db = DB::table('user_devices');
    }

    protected function generateDeviceIdentifier()
    {
        try {
            return bin2hex(random_bytes(16));
        } catch (Exception $e) {
            throw new Exception('Cihaz kimliği oluşturulamadı: ' . $e->getMessage());
        }
    }

    protected function extractDeviceName($userAgent)
    {
        $userAgent = strtolower($userAgent);

        if (strpos($userAgent, 'iphone') !== false) {
            return 'iPhone';
        } elseif (strpos($userAgent, 'android') !== false) {
            return 'Android';
        } elseif (strpos($userAgent, 'windows') !== false) {
            return 'Windows PC';
        } elseif (strpos($userAgent, 'macintosh') !== false) {
            return 'Macintosh';
        } else {
            return 'Bilinmeyen Cihaz';
        }
    }

    public function addDevice($userId, $userAgent, $lastLogin, $rememberMe = false)
    {
        $deviceName = $this->extractDeviceName($userAgent);
        $deviceIdentifier = $this->generateDeviceIdentifier();

        $data = [
            'user_id' => $userId,
            'device_name' => $deviceName,
            'device_identifier' => $deviceIdentifier,
            'user_agent' => $userAgent,
            'last_login' => $lastLogin,
            'remember_me' => $rememberMe
        ];

        $this->db->insert($data);

        return $data;
    }

    public function getDevice($deviceIdentifier)
    {
        return $this->db->where('device_identifier', $deviceIdentifier)->first();
    }
}
