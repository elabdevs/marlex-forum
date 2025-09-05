<?php

namespace App\Models;

class Setting {
    private $db;

    public function __construct() {
        $this->db = new DB('settings');
    }

    public function get($key) {
        $setting = $this->db->table('settings')->where('variable', $key)->first();
        return $setting ? $setting['value'] : null;
    }

    public function set($key, $value) {
        $existing = $this->db->table('settings')->where('key', $key)->first();
        if ($existing) {
            return $this->db->table('settings')->where('key', $key)->update(['value' => $value]);
        } else {
            return $this->db->table('settings')->insert(['key' => $key, 'value' => $value]);
        }
    }
}