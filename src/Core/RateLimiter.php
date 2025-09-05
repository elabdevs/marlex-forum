<?php

namespace App\Core;

use App\Models\DB;
use PDOException;

class RateLimiter
{
    private int $maxRequests;
    private int $timeWindow;
    private int $cooldownPeriod;
    private bool $enabled;
    private array $whitelistedIPs;
    private string $storageType;
    private string $storagePath;
    private bool $active = true;

    public function __construct()
    {
        $configFile = __DIR__ . '/config/config.php';

        if (!file_exists($configFile)) {
            $this->active = false;
            return;
        }

        $config = include $configFile;

        $this->enabled = true;
        $this->maxRequests = 10;
        $this->timeWindow = 60;
        $this->cooldownPeriod = 60;
        $this->whitelistedIPs = [];
        $this->storageType = 'file';
        $this->storagePath = __DIR__ . '/rate_limit_storage';

        if (isset($config['rate_limiter'])) {
            $rl = $config['rate_limiter'];
            $this->enabled = $rl['enabled'] ?? $this->enabled;
            $this->maxRequests = $rl['max_requests'] ?? $this->maxRequests;
            $this->timeWindow = $rl['time_window'] ?? $this->timeWindow;
            $this->cooldownPeriod = $rl['cooldown_period'] ?? $this->cooldownPeriod;
            $this->whitelistedIPs = isset($rl['whitelisted_ips']) ? explode(',', $rl['whitelisted_ips']) : $this->whitelistedIPs;
            $this->storageType = $rl['storage_type'] ?? $this->storageType;
            $this->storagePath = $rl['storage_path'] ?? $this->storagePath;
        }

        try {
            DB::table('rate_limiter_settings')->first();
        } catch (PDOException $e) {
            $this->storageType = 'file';
        }

        if ($this->storageType === 'file' && !is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0777, true);
        }
    }

    public function checkRateLimit(string $ip): bool
    {
        if (!$this->active || !$this->enabled || in_array($ip, $this->whitelistedIPs)) {
            return true;
        }

        return $this->storageType === 'file'
            ? $this->checkFileLimit($ip)
            : $this->checkDBLimit($ip);
    }

    private function checkFileLimit(string $ip): bool
    {
        $filePath = $this->storagePath . '/' . md5($ip) . '.txt';
        $currentTime = time();

        if (file_exists($filePath)) {
            $data = file_get_contents($filePath);
            $requests = explode("\n", $data);
            $requests = array_filter($requests, fn($t) => ($currentTime - (int)$t) < $this->timeWindow);

            if (count($requests) >= $this->maxRequests) {
                $firstRequestTime = min($requests);
                $remaining = $this->cooldownPeriod - ($currentTime - $firstRequestTime);
                if ($remaining > 0) return false;
            }
        } else {
            $requests = [];
        }

        $requests[] = $currentTime;
        file_put_contents($filePath, implode("\n", $requests));
        return true;
    }

    private function checkDBLimit(string $ip): bool
    {
        try {
            $currentTime = time();
            $count = DB::table('rate_limiter_logs')
                ->where('ip_address', $ip)
                ->where('timestamp', '>', $currentTime - $this->timeWindow)
                ->count();

            if ($count >= $this->maxRequests) {
                $firstRequest = DB::table('rate_limiter_logs')
                    ->where('ip_address', $ip)
                    ->orderBy('timestamp', 'ASC')
                    ->first();
                $remaining = $this->cooldownPeriod - ($currentTime - $firstRequest['timestamp']);
                return $remaining <= 0;
            }

            DB::table('rate_limiter_logs')->insert([
                'ip_address' => $ip,
                'timestamp' => $currentTime
            ]);

            return true;
        } catch (PDOException $e) {
            return $this->checkFileLimit($ip);
        }
    }
}
