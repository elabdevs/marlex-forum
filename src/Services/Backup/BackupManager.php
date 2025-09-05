<?php

namespace App\Services\Backup;

use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Exception;

class BackupManager
{
    private string $zipFile;
    private string $projectPath;
    private array $criticalPaths;
    private DatabaseDumper $dumper;
    private ProgressNotifier $notifier;

    public function __construct(
        DatabaseDumper $dumper,
        ProgressNotifier $notifier,
        string $projectPath = 'C:\xampp\htdocs',
        array $criticalPaths = [],
        ?string $zipName = null
    ) {
        $backupDir = $projectPath . '\public\backups';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0777, true);
        }

        $this->zipFile =
            $backupDir .
            DIRECTORY_SEPARATOR .
            ($zipName ?? "backup_" . time() . ".zip");

        $this->projectPath = $projectPath;
        $this->criticalPaths = $criticalPaths;
        $this->dumper = $dumper;
        $this->notifier = $notifier;

        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        header("Connection: keep-alive");
    }

    public function run(string $type = "critical"): void
    {
        try {
            $dumpFile = $this->dumper->dump();
            $paths = match ($type) {
                "critical" => $this->criticalPaths,
                "full" => [$this->projectPath],
                default => throw new Exception("Geçersiz backup type parametresi"),
            };

            $paths[] = $dumpFile;

            $this->createZip($paths);

            if (file_exists($dumpFile)) {
                unlink($dumpFile);
            }

            $sizeMB = round(filesize($this->zipFile) / 1024 / 1024, 2);
            $this->notifier->notify("Backup tamamlandı", 100);

            echo "data: " . json_encode([
                "status" => "success",
                "file" => "/public/backups/" . basename($this->zipFile),
                "size_mb" => $sizeMB,
            ]) . "\n\n";
            flush();
        } catch (Exception $e) {
            $this->notifier->notify("Hata: " . $e->getMessage(), 0);
            echo "data: " . json_encode([
                "status" => "error",
                "msg" => $e->getMessage(),
            ]) . "\n\n";
            flush();
        }
    }

    private function createZip(array $paths): void
    {
        $zip = new ZipArchive();
        if ($zip->open($this->zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new Exception("ZIP açılamadı");
        }

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                continue;
            }

            if (is_dir($path)) {
                $this->addDirToZip($zip, $path, basename($path));
            } else {
                $zip->addFile($path, basename($path));
                $this->notifier->notify("Dosya eklendi: " . basename($path), 10);
            }
        }

        $zip->close();
        $this->notifier->notify("ZIP oluşturuldu", 95);
    }

    private function addDirToZip(ZipArchive $zip, string $dir, string $base): void
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        $total = iterator_count($files);
        if ($total === 0) {
            return;
        }

        $added = 0;
        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $relative = $base . "/" . substr($filePath, strlen($dir) + 1);

            $file->isDir()
                ? $zip->addEmptyDir($relative)
                : $zip->addFile($filePath, $relative);

            $added++;
            $percent = 10 + round(($added / $total) * 80);
            $this->notifier->notify("Dosya ekleniyor: $relative", $percent);
        }
    }
}
