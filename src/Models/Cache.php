<?php

namespace App\Models;

use Exception;

class Cache {
    private $cacheDir;

    public function __construct($cacheDir = 'cache/') {
        $this->cacheDir = rtrim($cacheDir, '/') . '/';
        
        if (!file_exists($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }

    public function saveJsonToCache($jsonData, $cacheKey) {
        $cacheFile = $this->cacheDir . $cacheKey . '.json';
        $jsonEncoded = json_encode($jsonData, JSON_PRETTY_PRINT);

        if (file_put_contents($cacheFile, $jsonEncoded) !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function readJsonFromCache($cacheKey) {
        $cacheFile = $this->cacheDir . $cacheKey . '.json';

        if (file_exists($cacheFile)) {
            $jsonData = file_get_contents($cacheFile);
            return json_decode($jsonData, true);
        } else {
            return null;
        }
    }

    public function resetCache() {
        $cacheFiles = glob($this->cacheDir . '*');
    
        if ($cacheFiles === false) {
            throw new Exception("Cache dizini okunamıyor veya bulunamıyor.");
        }
    
        foreach ($cacheFiles as $file) {
            if (is_file($file) && is_writable($file)) {
                if (!unlink($file)) {
                    throw new Exception("Dosya silinemedi: $file");
                }
            } else {
                throw new Exception("Dosya okunamıyor veya silinemez: $file");
            }
        }
    }
    
}