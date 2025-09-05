<?php

namespace App\Controllers;

require "./vendor/autoload.php";

use App\Models\DB;
use App\Controllers\JsonKit;
use App\Controllers\SiteController;
use App\Controllers\ExportUserData;
use App\Core\Router;

class UsersController
{
    private $siteInfo;

    public function __construct()
    {
        $this->siteInfo = SiteController::getSiteInfo();
    }
    public static function listUsers()
    {
        $users = DB::table("users")->get();
        foreach ($users as &$user) {
            unset(
                $user["password_hash"],
                $user["email"],
                $user["last_ip"],
                $user["is_active"],
                $user["is_admin"],
                $user["preferences"],
                $user["userRole"],
                $user["userPoints"],
                $user["sessionId"],
                $user["last_password_change"],
                $user["email_verified_at"],
                $user["activation_code"],
                $user["profile_views"],
                $user["login_attempts"],
                $user["account_locked_until"],
                $user["last_password_reset_at"],
                $user["last_topic_activity"],
                $user["website"],
                $user["location"],
                $user["created_at"],
                $user["updated_at"],
                $user["last_login"]
            );

            $user["username"] = htmlspecialchars($user["username"]);
            $user["displayName"] = htmlspecialchars($user["displayName"]);
            $user["bio"] = htmlspecialchars($user["bio"]);
            $user["activityStatus"] = htmlspecialchars(
                ActivityController::checkActivity($user["id"])
                    ? "Çevrimiçi"
                    : "Çevrimdışı"
            );
            $user["userRole"] = self::convertUserRoleIdToStr(
                self::getHighestRoleId($user["id"])
            );
        }
        return $users;
    }

    public static function checkAuth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["user_id"]) && !empty($_COOKIE["remember_me"])) {
            $token = $_COOKIE["remember_me"];
            $tokenHash = hash("sha256", $token);

            $row = DB::table("remember_tokens")
                ->where("token_hash", $tokenHash)
                ->where("expire", ">", date("Y-m-d H:i:s"))
                ->first();

            if ($row) {
                $user = DB::table("users")
                    ->where("id", $row["user_id"])
                    ->first();

                if ($user) {
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["is_admin"] = $user["is_admin"];
                    return;
                }
            }

            setcookie("remember_me", "", time() - 3600, "/", "", true, true);
        }

        if (empty($_SESSION["user_id"])) {
            self::logout();
            die();
        }

        $checkSession = DB::table("users")
            ->where("id", $_SESSION["user_id"])
            ->first();
        if (!$checkSession) {
            self::logout();
            die();
        }
    }

    public static function checkUserId()
    {
        $userId = DB::filter($_SESSION["user_id"]);
        $sql = DB::table("users")
            ->where("id", $userId)
            ->first();
        if ($sql) {
            return $sql["id"];
        } else {
            self::logout();
            die();
        }
    }

    public static function checkUser($userId)
    {
        $userId = DB::filter($userId);
        $sql = DB::table("users")
            ->where("id", $userId)
            ->first();
        if ($sql) {
            return $sql["id"];
        } else {
            self::logout();
            die();
        }
    }

    public static function checkAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userId = DB::filter(
            isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null
        );
        if ($userId == null) {
            Router::notFound();
            die();
        }
        $sql = DB::table("users")
            ->where("id", $userId)
            ->first();
        if ($sql["is_admin"] == 1) {
            return;
        } else {
            Router::notFound();
            die();
        }
    }

    public static function logout()
    {
        ActivityController::saveOffline();
        $recaptcha = $_SESSION["recaptchaResponse"] ?? null;

        $_SESSION = [];

        if ($recaptcha !== null) {
            $_SESSION["recaptchaResponse"] = $recaptcha;
        }

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                "",
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header("Location: /login");
        exit();
    }

    public static function getUserInfo($userId)
    {
        return DB::table("users")
            ->where("id", $userId)
            ->first();
    }

    public static function getUserInfoByUsername($username)
    {
        return DB::table("users")
            ->where("username", $username)
            ->first();
    }

    public static function getUserBans()
    {
        return DB::table("bans")->get();
    }
    public static function updateUserPoints($userId, $point)
    {
        try {
            $currentPoints = self::getUserInfo($userId)["userPoints"];
            $newPoints = $currentPoints + $point;
            $sql = DB::table("users")
                ->where("id", $userId)
                ->update(["userPoints" => $newPoints]);
            if ($sql) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function getTotalUsersCount()
    {
        return count(DB::table("users")->get());
    }

    public static function getAllUsers()
    {
        return DB::table("users")->get();
    }

    public function getUserById($id)
    {
        if (is_numeric($id)) {
            if (@$_SESSION["is_admin"] == 1) {
                $sql = DB::table("users")
                    ->where("id", $id)
                    ->first();
                if ($sql) {
                    JsonKit::json($sql, "Kullanıcı Getirildi");
                } else {
                    JsonKit::fail("Kullanıcı Bulunamadı");
                }
            } else {
                JsonKit::fail("Yetkisiz Erişim!", 403);
            }
        } else {
            Router::notFound();
        }
    }

    public static function getAllRoles()
    {
        return DB::table("user_roles")->get();
    }
    public static function convertUserRoleIdToStr($id)
    {
        $role = DB::table("user_roles")
            ->where("id", $id)
            ->first();
        if ($role) {
            return $role["role_name"];
        } else {
            return "Kullanıcı";
        }
    }

    public static function getOnlineUsers()
    {
        try {
            $sql = DB::table("users")
                ->where("last_activity_time", ">", date("Y-m-d H:i:s"))
                ->where("is_admin", "!=", 1);
            if ($sql) {
                return $sql->get();
            } else {
                return [];
            }
        } catch (\Throwable $th) {
            return [];
        }
    }

    public static function getOnlineAdmins()
    {
        try {
            $sql = DB::table("users")
                ->where("last_activity_time", ">", date("Y-m-d H:i:s"))
                ->where("is_admin", 1);
            if ($sql) {
                return $sql->get();
            } else {
                return [];
            }
        } catch (\Throwable $th) {
            return [];
        }
    }

    public static function getUserInfoBySession()
    {
        if (isset($_SESSION["user_id"])) {
            return self::getUserInfo($_SESSION["user_id"]);
        } else {
            return null;
        }
    }

    public static function getUserProfile($userId)
    {
        if (is_numeric($userId)) {
            $userInfo = self::getUserInfo($userId);
            if (!$userInfo) {
                JsonKit::fail("Kullanıcı Bulunamadı");
                die();
            }
            JsonKit::json(
                [
                    "username" => $userInfo['username'],
                    "userRole" => $userInfo['userRole'],
                    "profile_picture" => $userInfo['profile_picture'],
                    "userPoints" => $userInfo['userPoints'],
                    "email" => $userInfo['email'],
                    "last_login" => $userInfo['last_login'],
                    "bio" => $userInfo['bio'],
                    "role" => self::convertUserRoleIdToStr(self::getHighestRoleId($userInfo["id"])),
                    "is_active" => $userInfo['is_active'],
                ],
                "Kullanıcı bilgileri getirildi."
            );
        } else {
            JsonKit::fail("Veri bulunamadı.");
        }
    }

    public static function getAllUserRoles()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $roles = DB::table("user_roles")->get();
        if ($roles) {
            foreach ($roles as $key => $role) {
                $roles[$key] = $role["role_name"];
            }
        } else {
            return false;
        }
        return $roles;
    }

    public static function getUserRoles($userId)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $userId = DB::filter($userId);
        if (
            $userId == ($_SESSION["user_id"] ?? null) ||
            ($_SESSION["is_admin"] ?? 0) == 1
        ) {
            $roles = DB::table("user_roles")
                ->join(
                    "user_role_assignments",
                    "id",
                    "=",
                    "user_role_assignments.role_id"
                )
                ->where("user_role_assignments.user_id", $userId)
                ->get();
            if ($roles) {
                foreach ($roles as $key => $role) {
                    $roles[$key] = $role["role_name"];
                }
            } else {
                echo JsonKit::failWithoutHRC(
                    "Kullanıcıya rol ataması yapılmamış"
                );
                return;
            }
            echo JsonKit::json($roles, "Roller getirildi");
        } else {
            echo JsonKit::fail("Yetkisiz Erişim");
        }
    }

    public static function getUserRolesArray($userId)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $userId = DB::filter($userId);
        if (
            $userId == ($_SESSION["user_id"] ?? null) ||
            ($_SESSION["is_admin"] ?? 0) == 1
        ) {
            $roles = DB::table("user_roles")
                ->join(
                    "user_role_assignments",
                    "id",
                    "=",
                    "user_role_assignments.role_id"
                )
                ->where("user_role_assignments.user_id", $userId)
                ->orderBy("user_role_assignments.role_id", "DESC")
                ->get();
            if ($roles) {
                foreach ($roles as $key => $role) {
                    $roles[$key] = $role["role_name"];
                }
            } else {
                return [];
            }
            return $roles;
        } else {
            return [];
        }
    }

    public static function getRoleCss($roleName)
    {
        $styles = DB::table("user_roles")
            ->where("role_name", $roleName)
            ->value("custom_css");
        return $styles ?? "background: #566573;";
    }

    public static function getHighestPermission($userId)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $userId = DB::filter($userId);

        if (
            $userId == ($_SESSION["user_id"] ?? null) ||
            ($_SESSION["is_admin"] ?? 0) == 1
        ) {
            $roles = DB::table("user_roles")
                ->join(
                    "user_role_assignments",
                    "id",
                    "=",
                    "user_role_assignments.role_id"
                )
                ->where("user_role_assignments.user_id", $userId)
                ->get();

            if (!$roles) {
                return null;
            }

            $highestPermission = null;
            foreach ($roles as $role) {
                if (
                    $highestPermission === null ||
                    $role["permission"] > $highestPermission
                ) {
                    $highestPermission = $role["permission"];
                }
            }

            return $highestPermission;
        }

        return null;
    }

    public static function getHighestRoleId($userId)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $userId = DB::filter($userId);

        $roles = DB::table("user_roles")
            ->join(
                "user_role_assignments",
                "id",
                "=",
                "user_role_assignments.role_id"
            )
            ->where("user_role_assignments.user_id", $userId)
            ->get();

        if (!$roles) {
            return null;
        }

        $highestId = max(array_column($roles, "role_id"));
        return $highestId;
    }

    public static function getRoleNameByRoleId($roleId)
    {
        $role = DB::table("user_roles")
            ->where("id", $roleId)
            ->first();
        return $role ? $role["role_name"] : null;
    }

    public static function getProfileViews($userId)
    {
        return DB::table("user_profile_views")
            ->where("viewed_user_id", $userId)
            ->count();
    }

    public function exportUserData()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
        if (!$userId) {
            JsonKit::fail("Oturum bulunamadı");
            return;
        }
        $exporter = new ExportUserData($userId);
        $zipPath = $exporter->export();

        header("Content-Type: application/zip");
        header('Content-Disposition: attachment; filename="user_data.zip"');
        readfile($zipPath);
        @unlink($zipPath);
        exit();
    }

    public function requestDeleteAccount()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

        if (!$userId) {
            JsonKit::fail("Oturum bulunamadı");
            return;
        }

        $result = DB::table("users")
            ->where("id", $userId)
            ->update([
                "is_active" => 0,
                "delete_request_at" => date("Y-m-d H:i:s"),
            ]);

        if ($result) {
            JsonKit::success("Hesap başarıyla silindi");
        } else {
            JsonKit::fail("Hesap silme işlemi başarısız");
        }
    }

    public function updateUser($data)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

        if (!$userId) {
            JsonKit::fail("Oturum bulunamadı");
            return;
        }

        $result = DB::table("users")
            ->where("id", $userId)
            ->update($data);

        if ($result) {
            JsonKit::success("Kullanıcı bilgileri başarıyla güncellendi");
        } else {
            JsonKit::fail("Kullanıcı güncelleme işlemi başarısız");
        }
    }
}
