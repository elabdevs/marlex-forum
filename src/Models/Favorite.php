<?php

namespace Models;

use Models\DB;

class Favorite {
    private $db;

    public function __construct() {
        $this->db = new DB('favorites');
    }

    public function addFavorite($topicId, $userId) {
        $this->db->table('favorites')->insert([
            'topic_id' => $topicId,
            'user_id' => $userId
        ]);
    }

    public function removeFavorite($topicId, $userId) {
        $this->db->table('favorites')->where('topic_id', $topicId)->where('user_id', $userId)->delete();
    }

    public function isFavorited($topicId, $userId) {
        return $this->db->table('favorites')->where('topic_id', $topicId)->where('user_id', $userId)->exists();
    }

    public function getFavoriteCount($topicId) {
        return $this->db->table('favorites')->where('topic_id', $topicId)->count();
    }

    public function checkFavorited($topicId, $userId) {
        return $this->db->table('favorites')->where('topic_id', $topicId)->where('user_id', $userId)->exists();
    }
}
