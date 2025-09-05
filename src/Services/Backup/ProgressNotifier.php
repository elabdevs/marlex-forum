<?php

namespace App\Services\Backup;

class ProgressNotifier
{
    public function notify(string $msg, int $percent): void
    {
        echo "data: " . json_encode([
            "msg" => $msg,
            "percent" => $percent
        ]) . str_repeat(" ", 1024) . "\n\n";
        
        flush();
    }
}
