<?php

namespace App\Controllers;

use App\Models\DB;
use App\Models\Crypto;
use App\Models\BannedWords;

class APIControllerV1
{
    private $secretKey = "MarlexNumberOne.*";

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function checkAuth()
    {
        $headers = getallheaders();
        $authHeader = $headers["Authorization"] ?? null;
        $token = null;

        if ($authHeader && preg_match("/Bearer\s(\S+)/", $authHeader, $matches)) {
            $token = $matches[1];
        }

        $payload = $this->verifyToken($token);

        if (!$payload) {
            JsonKit::fail("Token geçersiz veya süresi dolmuş");
            exit;
        }

        return $payload;
    }

    public function authorize()
    {
        try {
            $apiAuthUsername = $_POST["apiAuthUsername"] ?? null;
            $apiAuthKey = $_POST["apiAuthKey"] ?? null;

            if (!$apiAuthUsername || !$apiAuthKey) {
                return JsonKit::failWithoutHRC("apiAuthUsername ve apiAuthKey gerekli");
            }

            $user = DB::table("users")->where("username", $apiAuthUsername)->first();
            if (!$user) return JsonKit::failWithoutHRC("Kullanıcı bulunamadı");
            if (!hash_equals($user["apiAuthKey"], hash("sha256", $apiAuthKey))) return JsonKit::failWithoutHRC("apiAuthKey yanlış");
            if ($user["is_active"] != 1) return JsonKit::failWithoutHRC("Hesap devre dışı");

            $header = json_encode(["alg" => "HS256", "typ" => "JWT"]);
            $payload = json_encode([
                "userId" => $user["id"],
                "username" => $user["username"],
                "is_admin" => $user["is_admin"],
                "iat" => time(),
                "exp" => time() + 3600,
            ]);

            $base64UrlHeader = str_replace(["+", "/", "="], ["-", "_", ""], base64_encode($header));
            $base64UrlPayload = str_replace(["+", "/", "="], ["-", "_", ""], base64_encode($payload));
            $signature = hash_hmac("sha256", "$base64UrlHeader.$base64UrlPayload", $this->secretKey, true);
            $base64UrlSignature = str_replace(["+", "/", "="], ["-", "_", ""], base64_encode($signature));

            $jwt = "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";

            return JsonKit::success(["token" => $jwt, "expires_in" => 3600]);
        } catch (\Throwable $th) {
            return JsonKit::fail("Bir hata oluştu: " . $th->getMessage());
        }
    }

    public function verifyToken($token)
    {
        if (!$token) return false;
        $parts = explode(".", $token);
        if (count($parts) !== 3) return false;

        list($header, $payload, $signature) = $parts;

        $check = str_replace(["+", "/", "="], ["-", "_", ""], base64_encode(
            hash_hmac("sha256", "$header.$payload", $this->secretKey, true)
        ));

        if (!hash_equals($check, $signature)) return false;

        $data = json_decode(base64_decode($payload), true);
        return $data["exp"] < time() ? false : $data;
    }

    public function getUserIdByToken($token)
    {
        $payload = $this->verifyToken($token);
        return $payload["userId"] ?? null;
    }

    public function checkPermissionByToken($token)
    {
        $payload = $this->verifyToken($token);
        return $payload["is_admin"] ?? 0;
    }

    public function getUsers($userId = null)
    {
        $this->checkAuth();

        if ($userId) {
            $user = DB::table("users")
                ->where("id", $userId)
                ->select(["id","username","displayName","location","website","avatar_path"])
                ->first();
            if (!$user) return JsonKit::fail("Kullanıcı bulunamadı");
            return JsonKit::json($user, "Kullanıcı getirildi");
        }

        $users = DB::table("users")
            ->select(["id","username","displayName","location","website","avatar_path"])
            ->limit(10)
            ->get();

        return JsonKit::json($users, "Kullanıcılar getirildi");
    }

    public function getUserRoles($userId = null)
    {
        $this->checkAuth();

        if ($userId) {
            $roles = DB::table("user_roles")
                ->join("user_role_assignments","id","=","user_role_assignments.role_id")
                ->where("user_role_assignments.user_id", $userId)
                ->orderBy("user_role_assignments.role_id","DESC")
                ->get();

            foreach ($roles as $key => $role) {
                $roles[$key] = $role["role_name"];
            }
            return JsonKit::json($roles, "Kullanıcı rolleri getirildi");
        }

        $allRoles = DB::table("user_roles")->select(["id","role_name","custom_css"])->get();
        return JsonKit::json($allRoles, "Tüm kullanıcı rolleri getirildi");
    }

    public function getActiveUsers($minutes = null)
    {
        $this->checkAuth();

        if (!is_numeric($minutes) || $minutes <= 0) return JsonKit::fail("Geçersiz dakika değeri");

        $minutesAgo = date("Y-m-d H:i:s", strtotime("-$minutes minutes"));
        $activeUsers = DB::table("users")
            ->where("last_activity_time", ">", $minutesAgo)
            ->where("last_activity_time", "<", date("Y-m-d H:i:s"))
            ->get();

        foreach ($activeUsers as &$user) {
            unset(
                $user["password_hash"], $user["email"], $user["last_ip"], $user["is_active"], $user["is_admin"],
                $user["preferences"], $user["userRole"], $user["userPoints"], $user["sessionId"], $user["last_password_change"],
                $user["email_verified_at"], $user["activation_code"], $user["profile_views"], $user["login_attempts"],
                $user["account_locked_until"], $user["last_password_reset_at"], $user["last_topic_activity"],
                $user["website"], $user["location"], $user["created_at"], $user["updated_at"], $user["delete_request_at"],
                $user["apiAuthKey"], $user["last_login"]
            );
            $user["username"] = htmlspecialchars($user["username"]);
            $user["displayName"] = htmlspecialchars($user["displayName"]);
            $user["bio"] = htmlspecialchars($user["bio"]);
        }

        return !empty($activeUsers)
            ? JsonKit::json($activeUsers, "Son {$minutes} dakika içinde aktif olan kullanıcılar")
            : JsonKit::failWithoutHRC("Aktif kullanıcı bulunamadı");
    }

    public function getTopics()
    {
        $this->checkAuth();
        $topics = DB::table("topics")->limit(10)->get();
        foreach ($topics as &$topic) {
            $topic["content"] = htmlspecialchars(Crypto::decrypt($topic["content"]));
        }
        return JsonKit::json($topics, "Konular getirildi");
    }

    public function createTopic()
    {
        $this->checkAuth();
        $data = json_decode(file_get_contents("php://input"), true);

        $title = DB::filter($data["title"] ?? null);
        $description = DB::filter($data["description"] ?? null);
        $contentRaw = $data["content"] ?? null;
        $slug = DB::filter(isset($title) ? TopicsController::convertSlug($title) : null);
        $userId = $this->checkAuth()["userId"];
        $categoryId = DB::filter($data["category"] ?? null);
        $tagsInput = $data["tags"] ?? null;

        $bannedChecker = new BannedWords();
        if ($bannedChecker->checkMessage($title)["blocked"]) return JsonKit::fail("Başlık uygunsuz içerik içeriyor");
        if ($bannedChecker->checkMessage($contentRaw)["blocked"]) return JsonKit::fail("İçerik uygunsuz içerik içeriyor");

        $contentEncrypted = Crypto::encrypt($contentRaw);

        $topicId = DB::table("topics")->insert([
            "title" => $title,
            "description" => $description,
            "content" => $contentEncrypted,
            "slug" => $slug,
            "user_id" => $userId,
            "category_id" => $categoryId,
        ]);

        if (!empty($tagsInput) && is_array($tagsInput)) {
            foreach ($tagsInput as $tagName) {
                $tagName = trim($tagName);
                if (empty($tagName)) continue;

                $tag = DB::table("tags")->where("name", $tagName)->first();
                $tagId = $tag ? $tag["id"] : DB::table("tags")->insert(["name"=>$tagName]);

                DB::table("topic_tags")->insert(["topic_id"=>$topicId,"tag_id"=>$tagId]);
            }
        }

        return JsonKit::json(["slug"=>$slug], "Konu Başarıyla Açıldı");
    }

    public function removeTopic($topicId)
    {
        $this->checkAuth();
        $userId = $this->checkAuth()["userId"];
        $checkRemove = DB::table("topics")->where("id", $topicId);
        $topicInfo = $checkRemove->first();
        if (!$topicInfo) return JsonKit::fail("Konu bulunamadı",404);

        if ($topicInfo["user_id"] !== $userId && $this->checkPermissionByToken($this->checkAuth()["token"]) != 1) {
            return JsonKit::fail("Yetkiniz yok");
        }

        $checkRemove->update(["is_removed"=>1]);
        return JsonKit::success("Konu Silindi");
    }

    public function likeTopic($topicId)
    {
        $this->checkAuth();
        $userId = $this->checkAuth()["userId"];

        $checkLiked = DB::table("likes")->where("user_id",$userId)->where("post_id",$topicId)->where("is_active",1);

        if ($checkLiked->first()) {
            $checkLiked->update(["is_active"=>0]);
            return JsonKit::json(["like_count"=>TopicsController::getLikeCount($topicId)], "Konu Beğenisi Geri Çekildi");
        }

        DB::table("likes")->insert(["user_id"=>$userId,"post_id"=>$topicId,"is_active"=>1]);
        UsersController::updateUserPoints($userId,1);
        return JsonKit::json(["like_count"=>TopicsController::getLikeCount($topicId)], "Konu Beğenildi");
    }

    public function favoriteTopic($topicId)
    {
        $this->checkAuth();
        $userId = $this->checkAuth()["userId"];

        $checkFavorite = DB::table("favorites")->where("user_id",$userId)->where("topic_id",$topicId)->where("is_active",1);
        if ($checkFavorite->first()) {
            $checkFavorite->update(["is_active"=>0]);
            return JsonKit::json(["favorite_count"=>TopicsController::getFavoriteCount($topicId)], "Favorilerden Çıkarıldı");
        }

        DB::table("favorites")->insert(["user_id"=>$userId,"topic_id"=>$topicId,"is_active"=>1]);
        UsersController::updateUserPoints($userId,1);
        return JsonKit::json(["favorite_count"=>TopicsController::getFavoriteCount($topicId)], "Konu Favorilere Eklendi");
    }

    public function getRelatedTopics($topic)
    {
        $this->checkAuth();
        if (!$topic) return JsonKit::fail("Konu ID gerekli");
        $relatedTopics = TopicsController::getRelatedTopics($topic,5,true);
        return JsonKit::json($relatedTopics,"İlgili Konular Getirildi");
    }

    public function getCategoryTopics($categoryId)
    {
        $this->checkAuth();
        $topics = TopicsController::getTopicsByCategoryId($categoryId,true);
        return $topics ? JsonKit::json($topics,"Konu Başlıkları Getirildi") : JsonKit::fail("Konu bulunamadı",404);
    }

    public function getPostsByTopic($topicId)
    {
        $this->checkAuth();
        $input = json_decode(file_get_contents("php://input"), true);
        $input = ["page"=>DB::filter($input['page'] ?? 1),"per_page"=>DB::filter($input['per_page'] ?? 10)];

        $posts = TopicsController::getPostsByTopic($topicId,json_encode($input),true);
        return $posts ? JsonKit::json($posts,"Konu Başlıkları Getirildi") : JsonKit::fail("Konu bulunamadı",404);
    }

    public function replyContent($topicId)
    {
        $this->checkAuth();
        $userId = $this->checkAuth()["userId"];
        $input = json_decode(file_get_contents("php://input"), true);
        $postContent = Crypto::encrypt($input["content"] ?? null);

        if (!$postContent) return JsonKit::fail("Hatalı veri girişi");
        DB::table("posts")->insert(["user_id"=>$userId,"topic_id"=>$topicId,"content"=>$postContent]);
        return JsonKit::success("Yanıt Başarıyla Gönderildi");
    }

    public function reportPost()
    {
        $this->checkAuth();
        $userId = $this->checkAuth()["userId"];
        $input = json_decode(file_get_contents("php://input"), true);

        $post_id = DB::filter($input["post_id"] ?? null);
        $topic_id = DB::filter($input["topic_id"] ?? null);

        $checkTopic = DB::table("topics")->where("id",$topic_id)->first();
        $checkPost = DB::table("posts")->where("id",$post_id)->first();

        if (!$checkTopic) return JsonKit::fail("Konu bulunamadı",404);
        if (!$checkPost) return JsonKit::fail("Post bulunamadı",404);

        $checkReport = DB::table("reports")->where("user_id",$userId)->where("topic_id",$topic_id)->where("post_id",$post_id);
        if ($checkReport->first()) return JsonKit::fail("Zaten raporladınız");

        DB::table("reports")->insert(["user_id"=>$userId,"topic_id"=>$topic_id,"post_id"=>$post_id]);
        return JsonKit::success("Post raporlandı");
    }
}
