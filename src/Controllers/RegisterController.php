<?php
namespace App\Controllers;

use App\Controllers\SiteController;
use App\Models\DB;

class RegisterController
{
    private $siteInfo;

    public function __construct()
    {
        $this->siteInfo = SiteController::getSiteInfo();
    }

    public function registerUser()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);
            if (!$input) {
                echo JsonKit::failWithoutHRC("Geçersiz İstek");
                return;
            }
            $username = DB::filter($input["username"] ?? "");
            $password = DB::filter($input["password"] ?? "");
            $email = DB::filter($input["email"] ?? "");
            $newsletter = $input["newsletter"] ? 1 : 0;
            $fullName = DB::filter($input["fullName"] ?? "");

            if (empty($username) || empty($password) || empty($email)) {
                echo JsonKit::failWithoutHRC(
                    "Bütün Gerekli Alanları Doldurun."
                );
                return;
            }

            $checkUser = DB::table("users")
                ->where("username", $username)
                ->first();
            if ($checkUser) {
                echo JsonKit::failWithoutHRC(
                    "Bu Kullanıcı Adı Zaten Kullanımda."
                );
                return;
            }

            $checkEmail = DB::table("users")
                ->where("email", $email)
                ->first();
            if ($checkEmail) {
                echo JsonKit::failWithoutHRC("Bu Email Zaten Kullanımda.");
                return;
            }

            if (strlen($fullName) <= 2) {
                echo JsonKit::failWithoutHRC(
                    "Kullanıcı Adı 2 Karakterden Az Olamaz."
                );
                return;
            }


            if (strlen($fullName) >= 50) {
                echo JsonKit::failWithoutHRC(
                    "Kullanıcı Adı 50 Karakterden Fazla Olamaz."
                );
                return;
            }

            if (strlen($username) >= 20) {
                echo JsonKit::failWithoutHRC(
                    "Kullanıcı Adı 20 Karakterden Fazla Olamaz."
                );
                return;
            }

            if (strlen($username) <= 3) {
                echo JsonKit::failWithoutHRC(
                    "Kullanıcı Adı 3 Karakterden Az Olamaz."
                );
                return;
            }

            if (strlen($password) >= 32) {
                echo JsonKit::failWithoutHRC(
                    "Şifre 32 Karakterden Fazla Olamaz."
                );
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo JsonKit::failWithoutHRC("Geçerli bir email girin.");
                return;
            }

            if($newsletter !== 0 && $newsletter !== 1) {
                echo JsonKit::failWithoutHRC("Geçersiz bülten tercihi.");
                return;
            }

            if($newsletter == true){
                $newsletter = 1;
            } else {
                $newsletter = 0;
            }

            $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

            $insertUser = DB::table("users")->insert([
                "username" => $username,
                "password_hash" => $hashedPassword,
                "email" => $email,
                "email_notification" => $newsletter,
                "created_at" => date("Y-m-d H:i:s"),
            ]);

            if ($insertUser) {
                echo JsonKit::success("Kayıt Başarılı");
                exit();
            } else {
                echo JsonKit::failWithoutHRC("Kayıt işlemi başarısız oldu.");
            }
        } catch (\Throwable $th) {
            echo JsonKit::fail("Bir Hat Oluştu: " . $th->getMessage());
        }
    }
}
