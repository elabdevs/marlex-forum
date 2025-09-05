<?php

namespace App\Services\Backup;

use Exception;

class DatabaseDumper
{
    private string $host;
    private string $user;
    private string $pass;
    private string $dbName;
    private string $mysqldumpPath;

    public function __construct(
        string $host = "localhost",
        string $user = "root",
        string $pass = "",
        string $dbName = "marlexforum",
        string $mysqldumpPath = "C:\\xampp\\mysql\\bin\\mysqldump.exe"
    ) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->dbName = $dbName;
        $this->mysqldumpPath = $mysqldumpPath;
    }

    public function dump(): string
    {
        if (!file_exists($this->mysqldumpPath)) {
            throw new Exception("mysqldump bulunamadı: {$this->mysqldumpPath}");
        }

        $dumpFile = sys_get_temp_dir() . "/db_backup_" . time() . ".sql";
        $passPart = $this->pass ? "-p{$this->pass}" : "";

        $cmd = "\"{$this->mysqldumpPath}\" -h{$this->host} -u{$this->user} {$passPart} {$this->dbName} > \"{$dumpFile}\"";

        exec($cmd, $output, $retval);

        if ($retval !== 0 || !file_exists($dumpFile)) {
            throw new Exception("SQL dump oluşturulamadı");
        }

        return $dumpFile;
    }
}
