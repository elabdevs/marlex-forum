<?php

namespace App\Controllers;

use App\Models\DB;

class NotificationsController
{
    public static function addNotification($notificationData): bool
    {
        $db = new DB("notifications");

        try {
            if (
                !isset($notificationData["user_id"]) ||
                !isset($notificationData["message"])
            ) {
                return false;
            }

            $notificationId = $db->insert([
                "user_id" => $notificationData["user_id"],
                "type" => $notificationData["type"],
                "message" => $notificationData["message"],
            ]);

            if ($notificationId) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getNotificationsByUserId($userId)
    {
        try {
            $sql = DB::table("notifications")
                ->where("user_id", $userId)
                ->get();
            if ($sql) {
                return $sql;
            } else {
                return [];
            }
        } catch (\Throwable $th) {
            return [];
        }
    }
}
