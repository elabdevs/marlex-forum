<?php

namespace App\Controllers;

use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class RestoreBackup
{
    private $projectPath = 'C:\xampp\htdocs';
    private $dbHost = "localhost";
    private $dbUser = "root";
    private $dbPass = "";
    private $dbName = "marlexforum";
    private $debug = true;

    public function restoreBackup()
    {
        try {
            header("Content-Type: text/event-stream");
            header("Cache-Control: no-cache");
            header("Connection: keep-alive");
            @ob_end_clean();
            @ob_implicit_flush(1);

            $zipFile = $this->getZipFromInput();
            $this->restore($zipFile);
        } catch (\Exception $e) {
            $this->sendProgress("Hata: " . $e->getMessage(), 0);
        }
    }

    private function getZipFromInput()
    {
        if (
            !isset($_FILES["backup_zip"]) ||
            $_FILES["backup_zip"]["error"] !== UPLOAD_ERR_OK
        ) {
            throw new \Exception(
                "ZIP dosyası yüklenmedi veya yükleme hatası oluştu"
            );
        }

        $file = $_FILES["backup_zip"];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file["tmp_name"]);
        finfo_close($finfo);

        if (
            $mime !== "application/zip" &&
            $mime !== "application/octet-stream"
        ) {
            throw new \Exception("Geçersiz dosya türü: $mime");
        }

        $tmpFile = sys_get_temp_dir() . "/user_backup_" . time() . ".zip";
        if (!move_uploaded_file($file["tmp_name"], $tmpFile)) {
            throw new \Exception("ZIP dosyası geçici klasöre taşınamadı");
        }

        return $tmpFile;
    }

    private function restore($zipFile)
    {
        $this->sendProgress("Restore başlatıldı", 0);

        $tmpDir = sys_get_temp_dir() . "/restore_" . time();
        mkdir($tmpDir, 0777, true);

        $this->sendProgress("ZIP güvenli bir şekilde açılıyor", 10);
        $this->safeExtractZip($zipFile, $tmpDir);

        foreach (
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $tmpDir,
                    RecursiveDirectoryIterator::SKIP_DOTS
                )
            )
            as $file
        ) {
            if (preg_match('/\.sql$/', $file->getFilename())) {
                $this->validateSqlDump($file->getRealPath());
                $this->restoreDatabase($file->getRealPath());
            }
        }

        $this->restoreFiles($tmpDir);

        $this->sendProgress("Restore tamamlandı", 100);
    }

    private function safeExtractZip($zipPath, $targetDir)
    {
        $allowedExtensions = [
            "php",
            "html",
            "css",
            "js",
            "sql",
            "png",
            "jpg",
            "jpeg",
            "gif",
            "txt",
            "example",
            "env",
            "htaccess",
        ];

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new \Exception("ZIP açılamadı");
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = $zip->getNameIndex($i);

            if (
                strpos($entry, "../") !== false ||
                strpos($entry, "..\\") !== false
            ) {
                throw new \Exception("Güvensiz zip yolu: $entry");
            }

            $targetPath =
                rtrim($targetDir, DIRECTORY_SEPARATOR) .
                DIRECTORY_SEPARATOR .
                $entry;

            if (substr($entry, -1) === "/" || is_dir($targetPath)) {
                if (!is_dir($targetPath)) {
                    mkdir($targetPath, 0777, true);
                }
                continue;
            }

            $ext = pathinfo($entry, PATHINFO_EXTENSION);
            if (!in_array(strtolower($ext), $allowedExtensions)) {
                throw new \Exception("İzin verilmeyen uzantı: $entry");
            }

            $dir = dirname($targetPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $content = $zip->getFromIndex($i);
            file_put_contents($targetPath, $content);

            $this->sendProgress(
                "Dosya açıldı: $entry",
                round((($i + 1) / $zip->numFiles) * 100)
            );
        }

        $zip->close();
    }

    private function validateSqlDump($sqlFile)
    {
        $content = file_get_contents($sqlFile);
        if (preg_match("/DROP\s+DATABASE|DROP\s+TABLE/i", $content)) {
            throw new \Exception(
                "SQL dump güvenlik kontrolünden geçemedi: $sqlFile"
            );
        }
    }

    private function restoreDatabase($sqlFile)
    {
        $mysql = "C:\\xampp\\mysql\\bin\\mysql.exe";
        if (!file_exists($mysql)) {
            throw new \Exception("mysql.exe bulunamadı");
        }

        $passPart = $this->dbPass ? "-p{$this->dbPass}" : "";
        $cmd = "\"$mysql\" -h{$this->dbHost} -u{$this->dbUser} $passPart {$this->dbName} < \"$sqlFile\"";

        exec($cmd, $output, $retval);
        if ($retval !== 0) {
            throw new \Exception("SQL import başarısız: $sqlFile");
        }
        $this->sendProgress(
            "Veritabanı geri yüklendi: " . basename($sqlFile),
            100
        );
    }

    private function restoreFiles($sourceDir)
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $sourceDir,
                RecursiveDirectoryIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $total = iterator_count($files);
        $copied = 0;

        foreach ($files as $file) {
            $src = $file->getRealPath();
            $relPath = substr($src, strlen($sourceDir) + 1);
            $dest =
                rtrim($this->projectPath, DIRECTORY_SEPARATOR) .
                DIRECTORY_SEPARATOR .
                $relPath;

            if ($file->isDir()) {
                if (!is_dir($dest)) {
                    mkdir($dest, 0777, true);
                }
            } else {
                copy($src, $dest);
            }

            $copied++;
            $percent = round(($copied / $total) * 100);
            $this->sendProgress("Dosya kopyalandı: $relPath", $percent);
        }
    }

    private function sendProgress($msg, $percent)
    {
        if ($this->debug) {
            echo "data: " .
                json_encode(["msg" => $msg, "percent" => $percent]) .
                "\n\n";
            flush();
        }
    }
}
