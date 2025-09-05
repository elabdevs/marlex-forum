<?php

namespace App\Controllers;

use App\Models\DB;
use App\Controllers\JsonKit;

class ImageController
{
    private const MAX_BYTES = 2 * 1024 * 1024;
    private const ALLOWED_MIME = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp",
    ];
    private static $PUBLIC_DIR = __DIR__ . "/../../public/uploads/avatars";

    public static function uploadAvatar(): void
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (empty($_SESSION["user_id"])) {
                echo JsonKit::fail("Oturum bulunamadı");
                return;
            }
            $userId = (int) $_SESSION["user_id"];

            $csrfHeader = $_SERVER["HTTP_X_CSRF_TOKEN"] ?? "";
            if (
                empty($_SESSION["csrf_token"]) ||
                !hash_equals($_SESSION["csrf_token"], $csrfHeader)
            ) {
                echo JsonKit::fail("CSRF doğrulaması başarısız");
                return;
            }

            if (
                $_SERVER["REQUEST_METHOD"] !== "POST" ||
                empty($_FILES["avatar"]) ||
                $_FILES["avatar"]["error"] !== UPLOAD_ERR_OK
            ) {
                echo JsonKit::fail("Dosya alınamadı veya POST hatası");
                return;
            }
            $file = $_FILES["avatar"];

            $last = (int) ($_SESSION["last_avatar_upload"] ?? 0);
            if (time() - $last < 5) {
                echo JsonKit::fail("Çok sık deneme, lütfen bekle");
                return;
            }
            $_SESSION["last_avatar_upload"] = time();

            if ($file["size"] <= 0 || $file["size"] > self::MAX_BYTES) {
                echo JsonKit::fail("Dosya boyutu max 2MB olmalı");
                return;
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file["tmp_name"]) ?: "";
            if (!array_key_exists($mime, self::ALLOWED_MIME)) {
                echo JsonKit::fail("Geçersiz dosya türü");
                return;
            }

            $imgInfo = @getimagesize($file["tmp_name"]);
            if ($imgInfo === false) {
                echo JsonKit::fail("Geçersiz resim dosyası");
                return;
            }

            $contents = file_get_contents(
                $file["tmp_name"],
                false,
                null,
                0,
                4096
            );
            $suspicious = [
                "<?php",
                "<?=",
                "<%",
                "eval(",
                "shell_exec",
                "base64_decode(",
            ];
            foreach ($suspicious as $tok) {
                if (stripos($contents, $tok) !== false) {
                    echo JsonKit::fail("Dosya şüpheli içerik barındırıyor");
                    return;
                }
            }

            $userDir = rtrim(self::$PUBLIC_DIR, "/\\") . "/" . $userId;
            if (!is_dir($userDir)) {
                if (!mkdir($userDir, 0755, true) && !is_dir($userDir)) {
                    echo JsonKit::fail("Klasör oluşturulamadı");
                    return;
                }
            }
            self::ensureHtaccess($userDir);

            foreach (glob($userDir . "/*") as $old) {
                if (is_file($old)) {
                    @unlink($old);
                }
            }

            $ext = self::ALLOWED_MIME[$mime];
            $fileName = bin2hex(random_bytes(18)) . "." . $ext;
            $outPath = "{$userDir}/{$fileName}";

            if (!move_uploaded_file($file["tmp_name"], $outPath)) {
                echo JsonKit::fail("Dosya kaydedilemedi");
                return;
            }

            chmod($outPath, 0644);

            $relative = "/public/uploads/avatars/" . $userId . "/" . $fileName;
            DB::table("users")
                ->where("id", $userId)
                ->update([
                    "avatar_path" => $relative,
                    "avatar_updated_at" => date("Y-m-d H:i:s"),
                ]);

            echo JsonKit::json(["url" => $relative], "Avatar güncellendi");
        } catch (\Throwable $th) {
            error_log("Avatar upload error: " . $th->getMessage());
            echo JsonKit::fail("Bir hata oluştu: " . $th->getMessage());
        }
    }

    private static function ensureHtaccess(string $dir): void
    {
        $ht = $dir . "/.htaccess";
        if (!file_exists($ht)) {
            $content = <<<HT
                <FilesMatch "\.(php|phtml|phar|php[0-9])$">
                  Order allow,deny
                  Deny from all
                </FilesMatch>
                <FilesMatch "^\.">
                  Order allow,deny
                  Deny from all
                </FilesMatch>
                HT;
            @file_put_contents($ht, $content, LOCK_EX);
            @chmod($ht, 0644);
        }

        $idx = $dir . "/index.html";
        if (!file_exists($idx)) {
            @file_put_contents($idx, "<!doctype html><title></title>", LOCK_EX);
            @chmod($idx, 0644);
        }
    }
}
