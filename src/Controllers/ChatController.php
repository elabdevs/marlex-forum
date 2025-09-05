<?php

namespace App\Controllers;

use App\Models\Chat;
use App\Controllers\UsersController;
use App\Models\DB;
use App\Controllers\JsonKit;

class ChatController
{
    private Chat $chat;
    private UsersController $user;

    public function __construct()
    {
        $this->chat = new Chat();
        $this->user = new UsersController();
    }

    public function getMessagesFromDB(int $limit = 99999): array
    {
        $messages = $this->chat->getMessages($limit);
        $result = [];

        foreach ($messages as $msg) {
            $userInfo = $this->user->getUserInfo($msg['user_id']);
            $result[] = [
                'user' => $userInfo,
                'message' => $msg['message'],
                'created_at' => $msg['created_at']
            ];
        }

        return $result;
    }

    public function sendDM()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input["text"], $input["from"], $input["to"], $input["type"])) {
            JsonKit::fail("Geçersiz giriş", 400);
            return;
        }

        $from = (int)$input["from"];
        $to = (int)$input["to"];
        $message = trim($input["text"]);

        if (empty($message)) {
            JsonKit::fail("Mesaj boş olamaz", 400);
            return;
        }

        if (!$this->user->checkUser($from) || !$this->user->checkUser($to)) {
            JsonKit::fail("Geçersiz kullanıcı", 400);
            return;
        }

        $saved = $this->chat->saveDM([
            "msg_from" => DB::filter($from),
            "msg_to" => DB::filter($to),
            "content" => DB::filter($message),
        ]);

        $saved ? JsonKit::success("Mesaj gönderildi", 200) : JsonKit::fail("Mesaj gönderilemedi");
    }

    public function getDM(int $chatId)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION["user_id"] ?? null;

        if (!$userId) {
            JsonKit::fail("Kullanıcı doğrulanamadı", 401);
            return;
        }

        $offset = (int)($_GET["offset"] ?? 0);
        $count = (int)($_GET["count"] ?? 50);

        $messages = $this->chat->getMessagesFromDB($chatId, $userId, $count, $offset);

        if ($messages) {
            JsonKit::json($messages, "Mesajlar getirildi");
        } else {
            JsonKit::fail("Mesaj bulunamadı", 404);
        }
    }

    public function getLastMessageUsers()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION["user_id"] ?? null;

        if (!$userId) {
            JsonKit::fail("Kullanıcı doğrulanamadı", 401);
            return;
        }

        $messages = DB::table("direct_messages")
            ->whereRaw("(msg_from = ? OR msg_to = ?)", [$userId, $userId])
            ->orderBy("sended_at", "desc")
            ->get();

        $users = [];
        foreach ($messages as $msg) {
            $other = $msg["msg_from"] === $userId ? $msg["msg_to"] : $msg["msg_from"];
            if (!isset($users[$other])) {
                $users[$other] = [
                    "user_id" => $other,
                    "last_message" => $msg["content"],
                    "last_message_at" => $msg["sended_at"],
                ];
            }
        }

        foreach ($users as $index => $userData) {
            $info = $this->user->getUserInfo($userData["user_id"]);
            if ($info) {
                $activityStatus = ActivityController::checkActivity($info["id"]) ? "Çevrimiçi" : "Çevrimdışı";
                $users[$index] = array_merge($userData, [
                    "username" => $info["username"] ?? null,
                    "avatar" => $info["avatar_path"] ?? null,
                    "activityStatus" => $activityStatus
                ]);
            } else {
                unset($users[$index]);
            }
        }

        $users = array_values($users);
        $users ? JsonKit::json($users, "Son konuşulan kullanıcılar getirildi") : JsonKit::fail("Son konuşulan kullanıcılar bulunamadı", 404);
    }

    public function getLastMessageUsersArray(): array
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION["user_id"] ?? null;
        if (!$userId) return [];

        $messages = DB::table("direct_messages")
            ->whereRaw("(msg_from = ? OR msg_to = ?)", [$userId, $userId])
            ->orderBy("sended_at", "desc")
            ->get();

        $users = [];
        foreach ($messages as $msg) {
            $other = $msg["msg_from"] === $userId ? $msg["msg_to"] : $msg["msg_from"];
            if (!isset($users[$other])) {
                $users[$other] = [
                    "user_id" => $other,
                    "last_message" => $msg["content"],
                    "last_message_at" => $msg["sended_at"],
                ];
            }
        }

        foreach ($users as $index => $userData) {
            $info = $this->user->getUserInfo($userData["user_id"]);
            if ($info) {
                $activityStatus = ActivityController::checkActivity($info["id"]) ? "Çevrimiçi" : "Çevrimdışı";
                $users[$index] = array_merge($userData, [
                    "username" => $info["username"] ?? null,
                    "avatar" => $info["avatar_path"] ?? null,
                    "activityStatus" => $activityStatus
                ]);
            } else {
                unset($users[$index]);
            }
        }

        return array_values($users);
    }
}
