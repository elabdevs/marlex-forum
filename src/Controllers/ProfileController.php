<?php

namespace App\Controllers;

use App\Models\DB;
use PDOException;
use App\Controllers\JsonKit;

class ProfileController
{
    private $db;

    public function __construct()
    {
        $this->db = DB::table("users");
    }

    public function editProfile()
    {
        if (isset($_FILES["profile_picture"]) && isset($_POST["user_id"])) {
            $this->updateProfilePicture();
        } elseif (
            isset($_POST["email"]) ||
            (isset($_POST["password"]) && isset($_POST["old_password"]))
        ) {
            $this->updateEmailAndPassword();
        } else {
            JsonKit::fail("Geçersiz istek.");
        }
    }

    private function updateProfilePicture()
    {
        $user_id = $_POST["user_id"];
        $file = $_FILES["profile_picture"];

        $target_dir = "./public/uploads/profileImages/";
        $target_file = $target_dir . basename($file["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            JsonKit::fail("Bu dosya bir resim değil.");
            $uploadOk = 0;
        }

        if (file_exists($target_file)) {
            JsonKit::fail("Aynı dosya halihazırda veritabanında mevcut.");
            $uploadOk = 0;
        }

        if ($file["size"] > 3000000) {
            JsonKit::fail("Maksimum 1 MB boyutunda resim yükleyebilirsiniz.");
            $uploadOk = 0;
        }

        $allowed_extensions = ["jpg", "jpeg", "png"];
        if (!in_array($imageFileType, $allowed_extensions)) {
            JsonKit::fail("Sadece JPG, JPEG ve PNG dosyalarına izin verilir.");
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                try {
                    $this->db
                        ->where("id", $user_id)
                        ->update(["profile_picture" => $target_file]);

                    JsonKit::success("Profil fotoğrafı başarıyla yüklendi.");
                } catch (PDOException $e) {
                    JsonKit::fail("Veritabanı hatası: " . $e->getMessage());
                }
            } else {
                JsonKit::fail("Dosya yükleme hatası.");
            }
        }
    }

    private function updateEmailAndPassword()
    {
        $user_id = $_POST["user_id"];
        $email = isset($_POST["email"]) ? $_POST["email"] : null;
        $password = isset($_POST["password"]) ? $_POST["password"] : null;
        $old_password = isset($_POST["old_password"])
            ? $_POST["old_password"]
            : null;

        if ($email) {
            $email = DB::filter($email);
        }

        if ($password && $old_password) {
            $user = $this->db->where("id", $user_id)->first();
            if (!$user || !password_verify($old_password, $user["password"])) {
                JsonKit::fail("Eski şifre geçersiz.");
                return;
            }

            $hashed_password = password_hash($password, PASSWORD_ARGON2I);
        }

        $update_data = [];
        if ($email) {
            $update_data["email"] = $email;
        }

        if (isset($hashed_password)) {
            $update_data["password"] = $hashed_password;
        }

        if (empty($update_data)) {
            JsonKit::fail("Güncellenmesi gereken veri yok.");
            return;
        }

        try {
            $this->db->where("id", $user_id)->update($update_data);

            JsonKit::success("Profil başarıyla güncellendi.");
        } catch (PDOException $e) {
            JsonKit::fail("Veritabanı hatası: " . $e->getMessage());
        }
    }
}
