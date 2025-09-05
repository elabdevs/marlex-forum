<?php
namespace App\Services;

use App\Models\DB;

class ActivityService
{
    private int $afkInterval = 60;

    public function getUserLogs(?int $userId = null): array
    {
        $query = DB::table("activity_logs");
        if ($userId) {
            $query->where("user_id", $userId);
        }
        return $query->get();
    }

    public function logAction(string $action, int $userId): bool
    {
        try {
            DB::table("activity_logs")->insert([
                "user_id" => $userId,
                "action" => $action,
            ]);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function saveUserActivity(int $userId): bool
    {
        try {
            $lastActivity = DB::table("users")
                ->where("id", $userId)
                ->value("last_activity_time");

            $now = time();
            $lastTimestamp = $lastActivity ? strtotime($lastActivity) : 0;

            if (!$lastActivity || $now - $lastTimestamp > $this->afkInterval) {
                return DB::table("users")
                    ->where("id", $userId)
                    ->update(["last_activity_time" => date("Y-m-d H:i:s")]) > 0;
            }
            return false;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getActiveUsers(int $minutes = 5): array
    {
        $minutesAgo = date("Y-m-d H:i:s", strtotime("-$minutes minutes"));
        return DB::table("users")
            ->where("last_activity_time", ">", $minutesAgo)
            ->where("last_activity_time", "<", date("Y-m-d H:i:s"))
            ->get();
    }

    public function checkActivity(int $userId, int $minutes = 5): bool
    {
        $minutesAgo = date("Y-m-d H:i:s", strtotime("-$minutes minutes"));
        $lastActivity = DB::table("users")
            ->where("id", $userId)
            ->where("last_activity_time", ">", $minutesAgo)
            ->where("last_activity_time", "<", date("Y-m-d H:i:s"))
            ->first();
        return (bool)$lastActivity;
    }

    public function setAfk(int $userId, int $status): bool
    {
        return DB::table("users")
            ->where("id", $userId)
            ->update(["is_afk" => $status]) > 0;
    }
}
