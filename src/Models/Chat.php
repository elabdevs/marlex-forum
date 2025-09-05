<?php

namespace App\Models;

use App\Models\DB;

class Chat{
    private $db;

    public function __construct() {
        $this->db = new DB('chat');
    }

    public function getMessages($limit = 99999) {
        return $this->db->limit($limit)->get();
    }

    public function saveDM($data) {
        if($this->db->table('direct_messages')->insert($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getMessagesFromDB($chatId, $userId, $count = 50, $offset = 0) {
        return DB::table('direct_messages')
            ->whereRaw('
                (msg_to = ? AND msg_from = ?) 
                OR (msg_to = ? AND msg_from = ?)
            ', [$chatId, $userId, $userId, $chatId])
            ->orderBy('sended_at', 'DESC')
            ->offset($offset)
            ->limit($count)
            ->get();
    }

    public function removeMessage($messageId, $userId) {
        $this->db->where('id', $messageId)->where('user_id', $userId)->delete();
    }

    public function getMessageCount($userId = null) {
        if ($userId) {
            return $this->db->where('user_id', $userId)->count();
        }
        return $this->db->count();
    }

    public function isChatActive(){
        $value = $this->db->table('settings')->where("key", "chat_active")->first();
        if($value == true) {
            return true;
        } else {
            return false;
        }
    }
}
