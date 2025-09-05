<?php
namespace App\Controllers;

use App\Services\ActivityService;
use App\Utils\JsonKit;

class ActivityController
{
    private static ?ActivityService $service = null;

    private static function getService(): ActivityService
    {
        if (!self::$service) {
            self::$service = new ActivityService();
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }
        return self::$service;
    }

    private static function getUserId(): ?int
    {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user_id'] ?? null;
    }

    public static function getUserLogs(?int $userId = null): array
    {
        return self::getService()->getUserLogs($userId);
    }

    public static function log(string $action, int $userId): bool
    {
        return self::getService()->logAction($action, $userId);
    }

    public static function saveUserActivity(): void
    {
        $userId = self::getUserId();
        if (!$userId) return;

        self::getService()->saveUserActivity($userId);
    }

    public static function getActiveUsers(int $minutes = 5): void
    {
        $users = self::getService()->getActiveUsers($minutes);

        if (!empty($users)) {
            foreach ($users as &$user) {
                unset(
                    $user['password_hash'], $user['email'], $user['last_ip'], $user['is_active'],
                    $user['is_admin'], $user['preferences'], $user['userRole'], $user['userPoints'],
                    $user['sessionId'], $user['last_password_change'], $user['email_verified_at'],
                    $user['activation_code'], $user['profile_views'], $user['login_attempts'],
                    $user['account_locked_until'], $user['last_password_reset_at'], $user['last_topic_activity'],
                    $user['website'], $user['location'], $user['created_at'], $user['updated_at'],
                    $user['last_login']
                );

                $user['username'] = htmlspecialchars($user['username']);
                $user['displayName'] = htmlspecialchars($user['displayName']);
                $user['bio'] = htmlspecialchars($user['bio']);
            }
            echo JsonKit::json($users, "Son {$minutes} dakika içinde aktif olan kullanıcılar");
        } else {
            echo JsonKit::failWithoutHRC("Aktif kullanıcı bulunamadı");
        }
    }

    public static function checkActivity(int $userId): bool
    {
        return self::getService()->checkActivity($userId);
    }

    public static function saveAfk(): void
    {
        $userId = self::getUserId();
        if (!$userId) {
            echo JsonKit::failWithoutHRC("Yetkisiz Erişim.");
            return;
        }

        $result = self::getService()->setAfk($userId, 1);
        if ($result) {
            echo JsonKit::success("AFK modu açıldı");
        } else {
            echo JsonKit::failWithoutHRC("Zaten AFK modundasınız.");
        }
    }

    public static function removeAfk(): void
    {
        $userId = self::getUserId();
        if (!$userId) {
            echo JsonKit::failWithoutHRC("Yetkisiz Erişim.");
            return;
        }

        $result = self::getService()->setAfk($userId, 0);
        if ($result) {
            echo JsonKit::success("AFK modu kapatıldı");
        } else {
            echo JsonKit::failWithoutHRC("Zaten AFK modundasınız.");
        }
    }

    public static function saveOffline(): void
    {
        $userId = self::getUserId();
        if (!$userId) {
            echo JsonKit::failWithoutHRC("Yetkisiz Erişim.");
            return;
        }

        $result = self::getService()->setAfk($userId, 2);
        if ($result) {
            echo JsonKit::success("AFK modu açıldı");
        } else {
            echo JsonKit::failWithoutHRC("Zaten AFK modundasınız.");
        }
    }
}
