<?php

namespace App\Controllers;

use App\Controllers\JsonKit;

class SecurityController
{
    public static function generateCSRFToken()
    {
        $csrfToken = bin2hex(random_bytes(16));
        $_SESSION["csrf_token"] = $csrfToken;
        JsonKit::json(
            ["CSRFToken" => $csrfToken],
            "CSRF Token Başarıyla Üretildi"
        );
    }

    public static function verifyCSRFToken($csrfToken)
    {
        if ($csrfToken === $_SESSION["csrf_token"]) {
            JsonKit::success("CSRF Token Verisi Doğrulandı.");
        } else {
            JsonKit::fail("CSRF Token Verisi Geçersiz!");
        }
    }
}
