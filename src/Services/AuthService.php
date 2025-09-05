<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\DB;

class AuthService
{
    public function checkAuth(): bool
    {
        if (!isset($_SESSION["user_id"])) {
            return false;
        }

        $user = DB::table("users")
            ->where("id", $_SESSION["user_id"])
            ->first();

        return $user !== null;
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        header("Location: /login");
        exit();
    }
}
