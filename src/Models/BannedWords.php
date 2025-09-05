<?php

namespace App\Models;

class BannedWords
{
    protected $db;

    public function __construct()
    {
        $this->db = new DB('banned_words');
    }

    public function checkMessage(string $message): array
    {
        $bannedWords = $this->db->where('is_active', 1)->get();
        $reasons = [];

        foreach ($bannedWords as $word) {
            if ($word['is_regex']) {
                $pattern = '/' . str_replace('/', '\/', $word['pattern']) . '/i';
                if (preg_match($pattern, $message)) {
                    $reasons[] = $word['pattern'];
                    if ($word['replacement']) {
                        $message = preg_replace($pattern, $word['replacement'], $message);
                    } elseif ($word['severity'] === 'high') {
                        return ['blocked' => true, 'message' => $message, 'reasons' => $reasons];
                    }
                }
            } else {
                if (stripos($message, $word['pattern']) !== false) {
                    $reasons[] = $word['pattern'];
                    if ($word['replacement']) {
                        $message = str_ireplace($word['pattern'], $word['replacement'], $message);
                    } elseif ($word['severity'] === 'high') {
                        return ['blocked' => true, 'message' => $message, 'reasons' => $reasons];
                    }
                }
            }
        }

        return ['blocked' => false, 'message' => $message, 'reasons' => $reasons];
    }
}
