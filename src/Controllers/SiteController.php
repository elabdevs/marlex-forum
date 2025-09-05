<?php

namespace App\Controllers;

use App\Models\DB;
use App\Models\AuthSystem;
use DateTime;

class SiteController
{
    public static function getSiteInfo()
    {
        $row = DB::table("settings")
            ->where("variable", "siteInfo")
            ->first();

        if ($row) {
            $json = $row["value"];

            $data = json_decode($json, true);

            return [
                "siteTitle" => $data["data"]["siteName"] ?? "Default Title",
                "siteDescription" =>
                    $data["data"]["defaultSiteDescription"] ??
                    "Default Description",
                "siteKeywords" =>
                    $data["data"]["siteKeywords"] ?? "Default Keywords",
            ];
        } else {
            return [
                "siteTitle" => "Default Title",
                "siteDescription" => "Default Description",
                "siteKeywords" => "Default Keywords",
            ];
        }
    }

    public static function getSiteVariables()
    {
        $variables = DB::table("site_variables")->get();
        $result = [];

        foreach ($variables as $var) {
            $result[$var["name"]] = $var["value"];
        }

        return $result;
    }

    public static function getUserIP()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ips = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"]);
            $ip = trim($ips[0]);
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        } else {
            return "IP Geçersiz";
        }
    }

    public static function renderView(
        $fileName,
        $class = null,
        $function = null
    ) {
        self::includePartials("footer");
        $jumbotron = self::includePartials("jumbotron");
        $siteTitle = self::getSiteInfo()["siteTitle"];
        if (!empty($class) || !empty($function)) {
            $categories = $class::$function();
        }
        return include "./src/Views/Pages/$fileName.php";
    }

    public static function includePartials($partialName)
    {
        return file_get_contents("./src/Views/Partials/{$partialName}.php");
    }

    public static function sessionError(): void
    {
        http_response_code(404);
        echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>404 Not Found</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body {
                        background-color: #f8f9fa;
                    }
                    .error-container {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                    }
                    .error-content {
                        text-align: center;
                    }
                </style>
            </head>
            <body>
            
            <div class="error-container">
                <div class="error-content">
                    <h1 class="display-1">Hata!</h1>
                    <p class="lead">Giriş Yapmadan Bu Sayfaya Erişemezsiniz.</p>
                    <a href="/" class="btn btn-primary">Anasayfaya Dön</a>
                </div>
            </div>
            
            </body>
            </html>
            ';
    }

    public static function getSlugFromUrl()
    {
        $requestUri = $_SERVER["REQUEST_URI"];

        return $requestUri;
    }

    public function verifyCaptcha()
    {
        $authSystem = new AuthSystem();

        $recaptchaResponse = $_POST["g-recaptcha-response"];
        if ($recaptchaResponse) {
            $authSystem->createToken($recaptchaResponse);
        }
    }

    public static function getSiteVariable($name)
    {
        $variable = DB::table("site_variables")
            ->where("name", $name)
            ->first();
        return $variable ? $variable["value"] : null;
    }

    public function replaceVariablesInString(string $text): string
    {
        return preg_replace_callback(
            "/\{([a-zA-Z0-9_]+)\}/",
            function ($matches) {
                $val = self::getSiteVariable($matches[1]);
                return $val ?? $matches[0];
            },
            $text
        );
    }

    public function replaceVariablesInArray(array $data): array
    {
        array_walk_recursive($data, function (&$value) {
            if (is_string($value)) {
                $value = $this->replaceVariablesInString($value);
            }
        });
        return $data;
    }

    public static function timeAgo($datetime, $full = false)
    {
        $now = new DateTime();
        $ago = new DateTime($datetime);
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

    public static function saveLivePresence($topicId = null)
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $sessionId = session_id();
            $now = time();

            $check = DB::table("live_presence")
                ->where("session_id", $sessionId)
                ->first();

            if ($check) {
                $lastActive = strtotime($check["last_active"]);
                if ($now - $lastActive >= 60) {
                    $userId = isset($_SESSION["user_id"])
                        ? $_SESSION["user_id"]
                        : null;
                    DB::table("live_presence")
                        ->where("session_id", $sessionId)
                        ->update([
                            "user_id" => $userId,
                            "last_active" => date("Y-m-d H:i:s"),
                            "tab_id" => $_SERVER["REQUEST_URI"],
                            "current_url" => $_SERVER["REQUEST_URI"],
                            "topic_id" => $topicId,
                        ]);
                }
                return;
            } else {
                $userId = isset($_SESSION["user_id"])
                    ? $_SESSION["user_id"]
                    : null;
                $topicId = isset($topicId) ? $topicId : null;
                DB::table("live_presence")->insert([
                    "user_id" => $userId,
                    "session_id" => $sessionId,
                    "tab_id" => $_SERVER["REQUEST_URI"],
                    "current_url" => $_SERVER["REQUEST_URI"],
                    "topic_id" => $topicId,
                    "user_agent" => $_SERVER["HTTP_USER_AGENT"],
                    "ip" => $_SERVER["REMOTE_ADDR"],
                    "last_active" => date("Y-m-d H:i:s"),
                ]);
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function checkWhitelist($ip)
    {
        $query = DB::table("ip_whitelist")
            ->where("ip_address", $ip)
            ->first();
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public static function getIp()
    {
        $ip = null;

        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ips = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"]);
            $ip = trim($ips[0]);
        } elseif (!empty($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        } elseif (!empty($_SERVER["REMOTE_ADDR"])) {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        if ($ip === "::1") {
            $ip = "127.0.0.1";
        }

        return $ip;
    }
}
