<?php

namespace App\Controllers;

use App\Models\DB;

class ExportUserData
{
    private int $userId;
    private string $publicDir;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->publicDir = __DIR__ . "/../../public";
    }

    public function export(): string
    {
        $user = DB::table("users")
            ->where("id", $this->userId)
            ->first();
        if (!$user) {
            throw new \Exception("Kullanıcı bulunamadı");
        }

        $data = [
            "profile" => [
                "username" => $user["username"],
                "display_name" => $user["displayName"] ?? null,
                "bio" => $user["bio"] ?? null,
                "location" => $user["location"] ?? null,
                "website" => $user["website"] ?? null,
                "joined_at" => $user["created_at"] ?? null,
                "avatar_path" => $user["avatar_path"] ?? null,
                "banner_path" => $user["banner_path"] ?? null,
                "roles" => $this->getUserRoles(),
            ],

            "posts" => $this->getUserPosts(),
            "comments" => $this->getUserComments(),
        ];

        $jsonFile = tempnam(sys_get_temp_dir(), "userdata_") . ".json";
        file_put_contents(
            $jsonFile,
            json_encode(
                $data,
                JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_UNICODE |
                    JSON_UNESCAPED_SLASHES
            )
        );

        $zipFile = tempnam(sys_get_temp_dir(), "userdata_") . ".zip";
        $zip = new \ZipArchive();
        if ($zip->open($zipFile, \ZipArchive::CREATE) !== true) {
            throw new \Exception("ZIP oluşturulamadı");
        }

        $zip->addFile($jsonFile, "user_data.json");

        if (
            !empty($user["avatar_path"]) &&
            file_exists($this->publicDir . $user["avatar_path"])
        ) {
            $zip->addFile(
                $this->publicDir . $user["avatar_path"],
                "avatar" . $this->getExtension($user["avatar_path"])
            );
        }
        if (
            !empty($user["banner_path"]) &&
            file_exists($this->publicDir . $user["banner_path"])
        ) {
            $zip->addFile(
                $this->publicDir . $user["banner_path"],
                "banner" . $this->getExtension($user["banner_path"])
            );
        }

        $zip->close();
        @unlink($jsonFile);

        return $zipFile;
    }

    private function getUserRoles(): array
    {
        $roles = UsersController::getUserRolesArray($this->userId);

        return $roles;
    }

    private function getUserSettings(): array
    {
        return DB::table("user_settings")
            ->where("user_id", $this->userId)
            ->first() ?? [];
    }

    private function getUserPosts(): array
    {
        return DB::table("topics")
            ->where("user_id", $this->userId)
            ->select([
                "id",
                "title",
                "description",
                "content",
                "slug",
                "is_active",
                "created_at",
                "updated_at",
            ])
            ->get();
    }

    private function getUserComments(): array
    {
        return DB::table("posts")
            ->where("user_id", $this->userId)
            ->select(["id", "topic_id", "content", "created_at", "updated_at"])
            ->get();
    }

    private function getExtension(string $path): string
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        return $ext ? "." . $ext : "";
    }
}
