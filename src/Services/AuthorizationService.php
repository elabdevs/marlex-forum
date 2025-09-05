<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\DB;
use App\Core\Router;

class AuthorizationService
{
    public function checkAdmin(): bool
    {
        if (!isset($_SESSION["user_id"])) {
            return false;
        }

        $userId = DB::filter($_SESSION["user_id"]);
        $user = DB::table("users")
            ->where("id", $userId)
            ->first();

        if (!$user || $user["is_admin"] != 1) {
            Router::notFound();
            return false;
        }

        return true;
    }
}
