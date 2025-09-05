<?php

namespace Models;

use Models\DB;

class Like {
    private $db;

    public function __construct() {
        $this->db = new DB('likes');
    }

    public function addLike($topicId, $userId) {
        $this->db->table('likes')->insert([
            'post_id' => $topicId,
            'user_id' => $userId
        ]);
    }

    public function removeLike($topicId, $userId) {
        $this->db->table('likes')->where('post_id', $topicId)->where('user_id', $userId)->delete();
    }

    public function isLiked($topicId, $userId) {
        return $this->db->table('likes')->where('post_id', $topicId)->where('user_id', $userId)->exists();
    }

    public function getLikeCount($topicId) {
        return $this->db->table('likes')->where('post_id', $topicId)->count();
    }

    public function checkLiked($topicId, $userId) {
        return $this->db->table('likes')->where('post_id', $topicId)->where('user_id', $userId)->exists();
    }
}
