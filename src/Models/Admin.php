<?php

namespace App\Models;

use App\Models\DB;
use PDOException;

class Admin {

    protected static $table = 'settings';

    public static function saveSettings(array $settings) {
        foreach ($settings as $key => $value) {
            try {
                $existing = DB::table(self::$table)->where('variable', $key)->first();
                if ($existing) {
                    DB::table(self::$table)->where('variable', $key)->update(['value' => $value]);
                } else {
                    DB::table(self::$table)->insert(['variable' => $key, 'value' => $value]);
                }
            } catch (PDOException $e) {
                error_log('Admin saveSettings error: ' . $e->getMessage());
                return false;
            }
        }
        return true;
    }


    public static function get($key) {
        $row = DB::table(self::$table)->where('variable', $key)->first();
        if (!$row) return null;

        $value = $row['value'];
        if (is_string($value) && strlen($value) > 1 && $value[0] === '{') {
            return json_decode($value, true);
        }

        return $value;
    }

    public static function delete($key) {
        return DB::table(self::$table)->where('variable', $key)->delete();
    }

    public static function getAll() {
        $rows = DB::table(self::$table)->get();
        $settings = [];
        foreach ($rows as $row) {
            $value = $row['value'];
            if (is_string($value) && strlen($value) > 1 && $value[0] === '{') {
                $settings[$row['variable']] = json_decode($value, true);
            } else {
                $settings[$row['variable']] = $value;
            }
        }
        return $settings;
    }

    public static function update($key, $value) {
        if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        return DB::table(self::$table)->where('variable', $key)->update(['value' => $value]);
    }
}
