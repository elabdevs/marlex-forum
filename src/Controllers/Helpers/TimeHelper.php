<?php

declare(strict_types=1);

namespace App\Helpers;
use DateTime;

class TimeHelper
{
    public static function timeAgo(string $date, $full = false): string
    {
        $now = new DateTime();
        $ago = new DateTime($date);
        $diff = $now->diff($ago);

        $weeks = floor($diff->d / 7);
        $days = $diff->d % 7;

        $string = [
            "y" => "yıl",
            "m" => "ay",
            "d" => "gün",
            "h" => "saat",
            "i" => "dakika",
            "s" => "saniye",
        ];

        if ($weeks) {
            $string = ["w" => $weeks . " hafta"] + $string;
        }
        $diffValues = [
            "y" => $diff->y,
            "m" => $diff->m,
            "d" => $days,
            "h" => $diff->h,
            "i" => $diff->i,
            "s" => $diff->s,
        ];

        $result = [];
        foreach ($string as $k => $v) {
            if (isset($diffValues[$k]) && $diffValues[$k]) {
                $result[] = $diffValues[$k] . " " . $v . " önce";
            }
        }

        if (!$full) {
            $result = array_slice($result, 0, 1);
        }
        return $result ? implode(", ", $result) : "şimdi";
    }
}
