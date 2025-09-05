<?php

namespace App\Models;

use App\Models\DB;

class Topic {
    private $db;

    public function __construct() {
        $this->db = new DB('topics');
    }

    public function getActiveTopics($limit) {
        return $this->db->table('topics')->where('is_active', 1)->where('is_removed', 0)->where('is_archived', 0)->limit($limit)->get();
    }

    public function createTopic($title, $content, $slug, $userId, $categoryId) {
        return $this->db->table('topics')->insert([
            'title' => $title,
            'content' => $content,
            'slug' => $slug,
            'user_id' => $userId,
            'category_id' => $categoryId,
            'is_active' => 1
        ]);
    }

    public function getTopicBySlug($slug) {
        return $this->db->table('topics')->where('slug', $slug)->first();
    }

    public function getComments($topicId) {
        return $this->db->table('posts')->where('topic_id', $topicId)->get();
    }

    public function incrementTopicViews($topicId) {
        $this->db->table('topics')->where('id', $topicId)->increment('views');
    }

    public function getCategoryViewCounts() {
        return $this->db->table('categories')->select('name', 'view_count')->get();
    }

    public function getTopicsByCategoryId($categoryId) {
        return $this->db->table('topics')->where('category_id', $categoryId)->get();
    }

    public function getTopicInfo($topicId) {
        return $this->db->table('topics')->where('id', $topicId)->first();
    }
}
