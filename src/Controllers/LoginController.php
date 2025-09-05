<?php

namespace App\Controllers;

use App\Controllers\SiteController;
use App\Controllers\JsonKit;
use App\Controllers\ActivityController;
use App\Models\DB;

class LoginController
{
    private $siteInfo;

    public function __construct()
    {
        $this->siteInfo = SiteController::getSiteInfo();
    }

    public function loginUser()
    {
        try {
            $username = DB::filter(@$_POST["username"]);
            $password = DB::filter(@$_POST["password"]);
            $remember = !empty($_POST["remember_me"]);

            if (!empty($username)) {
                if (!empty($password)) {
                    $sql = DB::table("users")
                        ->where("username", $username)
                        ->first();
                    if ($sql) {
                        if (password_verify($password, $sql["password_hash"])) {
                            if ($sql["is_active"] == 1) {
                                if (session_status() === PHP_SESSION_NONE) {
                                    session_start();
                                }

                                $_SESSION["user_id"] = $sql["id"];
                                $_SESSION["is_admin"] = $sql["is_admin"];

                                ActivityController::log(
                                    "$username Adlı kullanıcı giriş yaptı.",
                                    $sql["id"]
                                );

                                DB::table("users")
                                    ->where("username", $username)
                                    ->update([
                                        "last_ip" => SiteController::getUserIP(),
                                        "last_login" => date("Y-m-d H:i:s"),
                                        "sessionId" => session_id(),
                                    ]);

                                if ($remember) {
                                    $token = bin2hex(random_bytes(32));
                                    $tokenHash = hash("sha256", $token);
                                    $expire = date(
                                        "Y-m-d H:i:s",
                                        strtotime("+30 days")
                                    );

                                    DB::table("remember_tokens")
                                        ->where("user_id", $sql["id"])
                                        ->delete();

                                    DB::table("remember_tokens")->insert([
                                        "user_id" => $sql["id"],
                                        "token_hash" => $tokenHash,
                                        "expire" => $expire,
                                    ]);

                                    setcookie(
                                        "remember_me",
                                        $token,
                                        time() + 60 * 60 * 24 * 30,
                                        "/",
                                        "",
                                        true,
                                        true
                                    );
                                }

                                echo JsonKit::success("Giriş Başarılı");
                            } else {
                                echo JsonKit::failWithoutHRC(
                                    "Hesabınız devre dışı bırakılmış. Lütfen yönetici ile iletişime geçin."
                                );
                            }
                        } else {
                            echo JsonKit::failWithoutHRC(
                                "Kullanıcı Adı Veya Şifre Yanlış."
                            );
                        }
                    } else {
                        echo JsonKit::failWithoutHRC(
                            "Kullanıcı Adı Veya Şifre Yanlış."
                        );
                    }
                } else {
                    echo JsonKit::failWithoutHRC("Şifre Gerekli.");
                }
            } else {
                echo JsonKit::failWithoutHRC("Kullanıcı Adı Gerekli.");
            }
        } catch (\Throwable $th) {
            echo JsonKit::fail("Bir hata oluştu: " . $th->getMessage());
        }
    }
}
