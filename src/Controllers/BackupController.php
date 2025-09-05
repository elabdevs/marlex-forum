<?php

namespace App\Controllers;

use App\Services\Backup\BackupManager;
use App\Services\Backup\DatabaseDumper;
use App\Services\Backup\ProgressNotifier;

class BackupController
{
    public function runBackup(string $type = "critical"): void
    {
        $dumper = new DatabaseDumper();
        $notifier = new ProgressNotifier();

        $criticalPaths = [
            'C:\xampp\htdocs\public\websocket\cert',
            'C:\xampp\htdocs\public\websocket\.env',
            'C:\xampp\htdocs\public\uploads',
            'C:\xampp\htdocs\assets\css\css_004',
            'C:\xampp\htdocs\assets\js\js_004',
            'C:\xampp\htdocs\.htaccess',
        ];

        $backup = new BackupManager($dumper, $notifier, 'C:\xampp\htdocs', $criticalPaths);
        $backup->run($type);
    }
}
