<?php

namespace App\Controllers;

require "./vendor/autoload.php";

use App\Models\DB;
use App\Models\Crypto;
use App\Models\BannedWords;
use App\Controllers\JsonKit;
use App\Controllers\SiteController;
use App\Controllers\NotificationsController;
use App\Models\AdvancedDBCache;
use PDO;
use PDOException;

class TopicsController
{
    public static function getTopics($limit = 9999)
    {
        try {
            $topicsDB = DB::table("topics")
                ->join("users", "user_id", "=", "users.id")
                ->select([
                    "topics.id",
                    "topics.title",
                    "topics.content",
                    "topics.is_active",
                    "topics.is_removed",
                    "topics.description",
                    "topics.user_id",
                    "topics.category_id",
                    "topics.views",
                    "topics.slug",
                    "topics.created_at",
                    "topics.updated_at",
                    "topics.is_active",
                    "users.username",
                ])
                ->where("topics.is_active", "=", 1)
                ->where("topics.is_removed", "=", 0)
                ->orderBy("topics.created_at", "DESC")
                ->limit($limit);

            $topicsCache = new AdvancedDBCache(
                $topicsDB,
                300,
                "topics:list:$limit"
            );
            return $topicsCache->get();
        } catch (\Throwable $th) {
            echo JsonKit::fail("Bir Hata Oluştu : " . $th->getMessage());
        }
    }

    public static function createTopic()
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $title = DB::filter(@$_POST["title"]);
            $description = DB::filter(@$_POST["description"]);
            $contentRaw = @$_POST["content"];
            $slug = self::convertSlug($title);
            $userId = DB::filter($_SESSION["user_id"]);
            $categoryId = DB::filter(@$_POST["category"]);
            $tagsInput = @$_POST["tags"];

            $bannedChecker = new BannedWords();

            $titleCheck = $bannedChecker->checkMessage($title);
            $contentCheck = $bannedChecker->checkMessage($contentRaw);

            if ($titleCheck["blocked"]) {
                echo JsonKit::fail(
                    "Başlık uygunsuz içerik içeriyor: " .
                        implode(", ", $titleCheck["reasons"])
                );
                exit();
            }

            if ($contentCheck["blocked"]) {
                echo JsonKit::fail(
                    "İçerik uygunsuz kelimeler içeriyor: " .
                        implode(", ", $contentCheck["reasons"])
                );
                exit();
            }

            $contentFiltered = $contentCheck["message"];

            if (
                empty($title) ||
                empty($contentFiltered) ||
                empty($slug) ||
                empty($userId) ||
                empty($categoryId)
            ) {
                echo JsonKit::fail("Tüm alanları doldurmalısınız.");
                exit();
            }

            $contentEncrypted = Crypto::encrypt($contentFiltered);

            $topicId = DB::table("topics")->insert([
                "title" => $title,
                "description" => $description,
                "content" => $contentEncrypted,
                "slug" => $slug,
                "user_id" => $userId,
                "category_id" => $categoryId,
            ]);

            if (!$topicId) {
                echo JsonKit::fail("Konu eklenirken bir hata oluştu.");
                exit();
            }

            if (!empty($tagsInput)) {
                $tagsArray = array_map("trim", explode(",", $tagsInput));
                foreach ($tagsArray as $tagName) {
                    if (empty($tagName)) {
                        continue;
                    }

                    $tag = DB::table("tags")
                        ->where("name", $tagName)
                        ->first();

                    if ($tag) {
                        $tagId = $tag["id"];
                    } else {
                        $tagId = DB::table("tags")->insert([
                            "name" => $tagName,
                        ]);
                    }

                    DB::table("topic_tags")->insert([
                        "topic_id" => $topicId,
                        "tag_id" => $tagId,
                    ]);
                }
            }

            echo JsonKit::json(
                [
                    "slug" => $slug,
                ],
                "Konu Başarıyla Açıldı"
            );
        } catch (\Throwable $th) {
            echo JsonKit::fail("Bir Hata Oluştu : " . $th->getMessage());
        }
    }

    public static function convertSlug($title)
    {
        $title = strtolower($title);
        $replace = [
            "ş" => "s",
            "Ş" => "S",
            "ı" => "i",
            "İ" => "I",
            "ç" => "c",
            "Ç" => "C",
            "ğ" => "g",
            "Ğ" => "G",
            "ö" => "o",
            "Ö" => "O",
            "ü" => "u",
            "Ü" => "U",
            " " => "-",
        ];
        $title = strtr($title, $replace);
        $title = preg_replace("/[^a-z0-9-]/", "", $title);
        $title = preg_replace("/-+/", "-", $title);
        $title = trim($title, "-");
        $title = $title . "-" . mt_rand(100000, 999999);
        return $title;
    }

    public static function getTopicViewCounts()
    {
        try {
            $categoriesDB = DB::table("categories");
            $categoriesCache = new AdvancedDBCache(
                $categoriesDB,
                300,
                "categories:list"
            );
            $categories = $categoriesCache->get();

            $newCategories = [];
            foreach ($categories as $category) {
                $topicCountDB = DB::table("topics")
                    ->where("category_id", $category["id"])
                    ->where("is_active", 1)
                    ->where("is_removed", 0)
                    ->select(["COUNT(*) AS count"]);
                $topicCountCache = new AdvancedDBCache(
                    $topicCountDB,
                    300,
                    "category:{$category["id"]}:topic_count"
                );
                $topicCountQuery = $topicCountCache->first();

                $latestTopicDB = DB::table("topics")
                    ->where("category_id", $category["id"])
                    ->where("is_active", 1)
                    ->where("is_removed", 0)
                    ->orderBy("created_at", "DESC")
                    ->limit(1);
                $latestTopicCache = new AdvancedDBCache(
                    $latestTopicDB,
                    300,
                    "category:{$category["id"]}:latest_topic"
                );
                $latestTopicQuery = $latestTopicCache->first();

                if ($latestTopicQuery) {
                    $category["latest_topic_title"] =
                        $latestTopicQuery["title"];
                    $category["latest_topic_date"] = SiteController::timeAgo(
                        $latestTopicQuery["created_at"]
                    );
                } else {
                    $category["latest_topic_title"] = "Yok";
                    $category["latest_topic_date"] = "Yok";
                }
                $category["latest_topic_author"] = $latestTopicQuery
                    ? UsersController::getUserInfo(
                        $latestTopicQuery["user_id"]
                    )["username"]
                    : "Yok";
                $category["topic_count"] = $topicCountQuery["count"];
                $newCategories[] = $category;
            }
            return $newCategories;
        } catch (\Throwable $th) {
            echo JsonKit::fail("Bir Hata Oluştu : " . $th->getMessage());
        }
    }

    public static function getTopicsByCategorySlug($slug)
    {
        try {
            $categoryIdDB = DB::table("categories")->where("slug", $slug);
            $categoryIdCache = new AdvancedDBCache(
                $categoryIdDB,
                300,
                "category_id:$slug"
            );
            $categoryId = $categoryIdCache->first();
            if ($categoryId) {
                $categoryId = $categoryId["id"];
            }
            $topicsDB = DB::table("topics")
                ->join("users", "user_id", "=", "users.id")
                ->select([
                    "topics.id",
                    "topics.title",
                    "topics.description",
                    "topics.is_pinned",
                    "topics.user_id",
                    "topics.category_id",
                    "topics.views",
                    "topics.slug",
                    "topics.created_at",
                    "topics.updated_at",
                    "topics.is_active",
                    "users.username",
                ])
                ->where("topics.category_id", "=", $categoryId)
                ->where("topics.is_active", "=", 1)
                ->where("topics.is_removed", "=", 0);
            $topicsCache = new AdvancedDBCache(
                $topicsDB,
                300,
                "category_topics:$categoryId"
            );
            $categories = $topicsCache->get();

            foreach ($categories as &$category) {
                $category["replies"] = self::getReplyCount($category["id"]);
                $category["likes"] = self::getLikeCount($category["id"]);
                $category["excerpt"] = $category["description"];
                $category["author"] = $category["username"];
                unset($category["description"]);
                unset($category["is_active"]);
                unset($category["username"]);
            }
            if ($categories) {
                echo JsonKit::json($categories, "Kategoriler Getirildi");
            } else {
                echo JsonKit::fail("Konu bulunamadı.", 404);
            }
        } catch (\Throwable $th) {
            echo JsonKit::fail("Bir Hata Oluştu : " . $th->getMessage());
        }
    }

    public static function getTopicsByCategoryId($id, $array = false)
    {
        try {
            $categoryIdDB = DB::table("categories")->where("id", $id);
            $categoryIdCache = new AdvancedDBCache(
                $categoryIdDB,
                300,
                "category_id:$id"
            );
            $categoryId = $categoryIdCache->first();
            if ($categoryId) {
                $categoryId = $categoryId["id"];
            }
            $topicsDB = DB::table("topics")
                ->join("users", "user_id", "=", "users.id")
                ->select([
                    "topics.id",
                    "topics.title",
                    "topics.description",
                    "topics.is_pinned",
                    "topics.user_id",
                    "topics.category_id",
                    "topics.views",
                    "topics.slug",
                    "topics.created_at",
                    "topics.updated_at",
                    "topics.is_active",
                    "users.username",
                ])
                ->where("topics.category_id", "=", $categoryId)
                ->where("topics.is_active", "=", 1)
                ->where("topics.is_removed", "=", 0);
            $topicsCache = new AdvancedDBCache(
                $topicsDB,
                300,
                "category_topics:$categoryId"
            );
            $categories = $topicsCache->get();

            foreach ($categories as &$category) {
                $category["replies"] = self::getReplyCount($category["id"]);
                $category["likes"] = self::getLikeCount($category["id"]);
                $category["excerpt"] = $category["description"];
                $category["author"] = $category["username"];
                unset($category["description"]);
                unset($category["is_active"]);
                unset($category["username"]);
            }
            if($array){
                if ($categories) {
                    return $categories;
                }
            } else {
                if ($categories) {
                    echo JsonKit::json($categories, "Kategoriler Getirildi");
                } else {
                    echo JsonKit::fail("Konu bulunamadı.", 404);
                }
            }
        } catch (\Throwable $th) {
            echo JsonKit::fail("Bir Hata Oluştu : " . $th->getMessage());
        }
    }

    public static function getLikeCount($topicId)
    {
        try {
            $likeCount = DB::table("likes")
                ->where("post_id", "=", $topicId)
                ->where("is_active", 1)
                ->select(["COUNT(*) AS like_count"])
                ->first();
        
            return $likeCount ? (int) $likeCount["like_count"] : 0;
        } catch (\Throwable $e) {
            error_log("Veritabanı hatası: " . $e->getMessage());
            return 0;
        }
    }
    
    public static function getFavoriteCount($topicId)
    {
        try {
            $favoriteCount = DB::table("favorites")
                ->where("topic_id", "=", $topicId)
                ->where("is_active", 1)
                ->select(["COUNT(*) AS like_count"])
                ->first();
        
            return $favoriteCount ? (int) $favoriteCount["like_count"] : 0;
        } catch (\Throwable $e) {
            error_log("Veritabanı hatası: " . $e->getMessage());
            return 0;
        }
    }

    public static function getReplyCount($topicId)
    {
        try {
            $replyCountDB = DB::table("posts")
                ->where("topic_id", "=", $topicId)
                ->select(["COUNT(*) AS reply_count"]);
            $replyCountCache = new AdvancedDBCache(
                $replyCountDB,
                60,
                "reply_count:$topicId"
            );
            $replyCount = $replyCountCache->first();

            return (int) $replyCount["reply_count"];
        } catch (\Throwable $e) {
            error_log("Veritabanı hatası: " . $e->getMessage());
            return 0;
        }
    }

    public static function checkLiked($topicId, $userId): bool
    {
        try {
            $checkSqlDB = DB::table("likes")
                ->where("post_id", $topicId)
                ->where("user_id", $userId)
                ->where("is_active", 1);
            $checkSqlCache = new AdvancedDBCache(
                $checkSqlDB,
                60,
                "liked:$topicId:$userId"
            );
            if ($checkSqlCache->first()) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function checkFavorited($topicId, $userId)
    {
        try {
            $checkSqlDB = DB::table("favorites")
                ->where("topic_id", $topicId)
                ->where("user_id", $userId)
                ->where("is_active", 1);
            $checkSqlCache = new AdvancedDBCache(
                $checkSqlDB,
                60,
                "favorited:$topicId:$userId"
            );
            if ($checkSqlCache->first()) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function likeTopic()
    {
        try {
            session_start();
            $topicId = DB::filter(@$_POST["topic_id"]);
            $userId = DB::filter(@$_SESSION["user_id"]);
            if (empty($userId)) {
                echo JsonKit::failWithoutHRC(
                    "Giriş yapmadan konu beğenilemez."
                );
                exit();
            }

            $checkLiked = DB::table("likes")
                ->where("user_id", $userId)
                ->where("post_id", $topicId)
                ->where("is_active", 1);

            if ($checkLiked->first()) {
                $checkLiked->update([
                    "is_active" => 0,
                ]);
                echo JsonKit::json(
                    [
                        "like_count" => self::getLikeCount($topicId),
                    ],
                    "Konu Beğenisi Geri Çekildi"
                );
                exit();
            }

            $topicInfo = self::getTopicInfo($topicId);
            $likedUserInfo = UsersController::getUserInfo($userId);

            $likedUsername = $likedUserInfo["username"];
            $topicTitle = $topicInfo["title"];
            $topicOwner = $topicInfo["user_id"];

            $sql = DB::table("likes")
                ->where("user_id", $userId)
                ->where("post_id", $topicId)
                ->where("is_active", 0);
            date_default_timezone_set("Europe/Istanbul");
            if ($sql->first()) {
                $sql->update([
                    "is_active" => 1,
                    "liked_at" => date("Y-m-d H:i:s"),
                ]);
                echo JsonKit::json(
                    [
                        "like_count" => self::getLikeCount($topicId),
                    ],
                    "Konu Beğenildi"
                );
                $notificationData = [
                    "user_id" => $topicOwner,
                    "type" => "like",
                    "message" =>
                        $likedUsername .
                        " Adlı Kullanıcı, " .
                        $topicTitle .
                        " Adlı Konunuzu Beğendi.",
                ];
                NotificationsController::addNotification($notificationData);
            } else {
                $sql = DB::table("likes")->insert([
                    "user_id" => $userId,
                    "post_id" => $topicId,
                    "is_active" => 1,
                ]);
                UsersController::updateUserPoints($userId, 1);
                echo JsonKit::json(
                    [
                        "like_count" => self::getLikeCount($topicId),
                    ],
                    "Konu Beğenildi"
                );
                $notificationData = [
                    "user_id" => $topicOwner,
                    "type" => "like",
                    "message" =>
                        $likedUsername .
                        " Adlı Kullanıcı, " .
                        $topicTitle .
                        " Adlı Konunuzu Beğendi.",
                ];
                NotificationsController::addNotification($notificationData);
            }
        } catch (\Throwable $th) {
            echo JsonKit::fail("Bir Hata Oluştu : " . $th->getMessage());
        }
    }

    public function favoriteTopic()
    {
        try {
            session_start();
            $topicId = DB::filter(@$_POST["topic_id"]);
            $userId = DB::filter(@$_SESSION["user_id"]);
            if (empty($userId)) {
                echo JsonKit::failWithoutHRC(
                    "Giriş yapmadan konu favorilere eklenemez."
                );
                exit();
            }

            $checkFavorite = DB::table("favorites")
                ->where("user_id", $userId)
                ->where("topic_id", $topicId)
                ->where("is_active", 1);

            if ($checkFavorite->first()) {
                $checkFavorite->update([
                    "is_active" => 0,
                ]);
                echo JsonKit::json(
                    [
                        "favorite_count" => self::getFavoriteCount($topicId),
                    ],
                    "Favorilerden Çıkarıldı."
                );
                exit();
            }

            $topicInfo = self::getTopicInfo($topicId);
            $favoritedUserInfo = UsersController::getUserInfo($userId);

            $likedUsername = $favoritedUserInfo["username"];
            $topicTitle = $topicInfo["title"];
            $topicOwner = $topicInfo["user_id"];

            $sql = DB::table("favorites")
                ->where("user_id", $userId)
                ->where("topic_id", $topicId)
                ->where("is_active", 0);

            if ($sql->first()) {
                $sql->update([
                    "is_active" => 1,
                ]);
                
                echo JsonKit::json(
                    [
                        "favorite_count" => self::getFavoriteCount($topicId),
                    ],
                    "Konu Favorilere Eklendi"
                );
                $notificationData = [
                    "user_id" => $topicOwner,
                    "type" => "like",
                    "message" =>
                        $likedUsername .
                        " Adlı Kullanıcı, " .
                        $topicTitle .
                        " Adlı Konunuzu Favorilerine Ekledi.",
                ];
                NotificationsController::addNotification($notificationData);
            } else {
                $sql = DB::table("favorites")->insert([
                    "user_id" => $userId,
                    "topic_id" => $topicId,
                    "is_active" => 1,
                ]);
                UsersController::updateUserPoints($userId, 1);
                JsonKit::json(
                    [
                        "favorite_count" => self::getFavoriteCount($topicId),
                    ],
                    "Konu Favorilere Eklendi"
                );
                $notificationData = [
                    "user_id" => $topicOwner,
                    "type" => "favorite",
                    "message" =>
                        $likedUsername .
                        " Adlı Kullanıcı, " .
                        $topicTitle .
                        " Adlı Konunuzu Favorilerine Ekledi.",
                ];
                NotificationsController::addNotification($notificationData);
            }
        } catch (\Throwable $th) {
            echo JsonKit::fail("Bir Hata Oluştu : " . $th->getMessage());
        }
    }

    public function archiveTopic()
    {
        try {
            session_start();
            $topicId = DB::filter(@$_POST["topic_id"]);
            $userId = DB::filter(@$_SESSION["user_id"]);
            if (empty($_SESSION["is_admin"])) {
                echo JsonKit::failWithoutHRC(
                    "Konu arşivlemek için yetkiniz yok."
                );
                exit();
            }

            $checkArchive = DB::table("topics")->where("id", $topicId);

            if ($checkArchive->first()["is_active"] == 1) {
                $checkArchive->update([
                    "is_active" => 0,
                ]);
                echo JsonKit::success("Konu Arşivlendi.");
                exit();
            } else {
                $checkArchive->update([
                    "is_active" => 1,
                ]);
                echo JsonKit::success("Konu Arşivden Çıkarıldı.");
            }
        } catch (\Throwable $th) {
            echo JsonKit::fail("Bir Hata Oluştu : " . $th->getMessage());
        }
    }

    public function removeTopic()
    {
        try {
            session_start();
            $topicId = DB::filter(@$_POST["topic_id"]);
            if (empty($_SESSION["is_admin"])) {
                echo JsonKit::failWithoutHRC("Konu silmek için yetkiniz yok.");
                exit();
            }

            $checkRemove = DB::table("topics")->where("id", $topicId);

            if ($checkRemove->first()["is_removed"] == 1) {
                $checkRemove->update([
                    "is_removed" => 0,
                ]);
                echo JsonKit::success("Konu Geri Yüklendi.");
                exit();
            } else {
                $checkRemove->update([
                    "is_removed" => 1,
                ]);
                echo JsonKit::success("Konu Silindi.");
            }
        } catch (\Throwable $th) {
            echo JsonKit::fail("Bir Hata Oluştu : " . $th->getMessage());
        }
    }

    public static function checkRemoved($topicId)
    {
        try {
            $checkSql = DB::table("topics")
                ->where("id", $topicId)
                ->where("is_removed", 1);

            if ($checkSql->first()) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function getTopicInfo($topicId)
    {
        try {
            $topicDB = DB::table("topics")
                ->join("users", "user_id", "=", "users.id")
                ->select([
                    "topics.id",
                    "topics.title",
                    "topics.content",
                    "topics.user_id",
                    "topics.category_id",
                    "topics.views",
                    "topics.slug",
                    "topics.created_at",
                    "topics.updated_at",
                    "topics.is_active",
                    "users.username",
                ])
                ->where("topics.id", $topicId)
                ->where("topics.is_active", "=", 1)
                ->where("topics.is_removed", "=", 0);
            $topicCache = new AdvancedDBCache(
                $topicDB,
                300,
                "topic_info:$topicId"
            );
            $topic = $topicCache->first();
            if ($topic) {
                return $topic;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            echo JsonKit::fail("Bir Hata Oluştu : " . $th->getMessage());
        }
    }

    public static function getTotalTopicCount()
    {
        return count(DB::table("topics")->get());
    }

    public static function getAllTagsJson($topicId)
    {
        try {
            $tagsDB = DB::table("tags")
                ->join("topic_tags", "id", "=", "topic_tags.tag_id")
                ->where("topic_tags.topic_id", $topicId);
            $tagsCache = new AdvancedDBCache($tagsDB, 300, "tags:$topicId");
            $tags = $tagsCache->get();

            return $tags;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function getPostCountsByTopicId($topicId)
    {
        try {
            $postsDB = DB::table("posts")->where("topic_id", $topicId);
            $postsCache = new AdvancedDBCache(
                $postsDB,
                60,
                "post_count:$topicId"
            );
            $posts = $postsCache->get();
            if ($posts) {
                return count($posts);
            } else {
                return "0";
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function getTopicsCountByUserId($userId)
    {
        try {
            $countDB = DB::table("topics")
                ->where("is_active", 1)
                ->where("is_removed", 0)
                ->where("user_id", $userId);
            $countCache = new AdvancedDBCache(
                $countDB,
                60,
                "user_topics_count:$userId"
            );
            $count = $countCache->count();
            if ($count > 0) {
                return $count;
            } else {
                return 0;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function getTopicOnlines($topicId)
    {
        try {
            $onlineUsersDB = DB::table("live_presence")
                ->where("topic_id", $topicId)
                ->where(
                    "last_active",
                    ">",
                    date("Y-m-d H:i:s", strtotime("-5 minutes"))
                );
            $onlineUsersCache = new AdvancedDBCache(
                $onlineUsersDB,
                30,
                "topic_onlines:$topicId"
            );
            $onlineUsers = $onlineUsersCache->get();

            foreach ($onlineUsers as &$user) {
                if ($user["user_id"]) {
                    $userInfo = UsersController::getUserInfo($user["user_id"]);
                    $user["username"] = $userInfo["username"];
                    $user["profile_picture"] = isset(
                        $userInfo["profile_picture"]
                    )
                        ? $userInfo["profile_picture"]
                        : "/assets/images/default-avatar.png";
                } else {
                    $user["username"] = null;
                    $user["profile_picture"] =
                        "/assets/images/default-avatar.png";
                }
            }

            if ($onlineUsers) {
                echo JsonKit::json(
                    $onlineUsers,
                    "Konu üzerindeki aktif kullanıcılar"
                );
            } else {
                echo JsonKit::fail("Aktif Kullanıcı Bulunamadı.");
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function getPostsByTopic($topicId, $data = null, $array = false)
    {
        try {
            $data = json_decode($data, true);
            if($array){
                $page = $data['page'];
                $perPage = $data['per_page'];
            } else {
                $page = isset($_GET["page"]) ? max(1, intval($_GET["page"])) : 1;
                $perPage = isset($_GET["per_page"])
                    ? min(50, intval($_GET["per_page"]))
                    : 10;
            }
            $offset = ($page - 1) * $perPage;

            $totalDB = DB::table("posts")
                ->where("topic_id", $topicId)
                ->where("is_active", 1);
            $totalCache = new AdvancedDBCache(
                $totalDB,
                60,
                "topic_posts_total:$topicId"
            );
            $total = $totalCache->count();

            $postsDB = DB::table("posts")
                ->join("users", "user_id", "=", "users.id")
                ->select([
                    "posts.id",
                    "posts.content",
                    "posts.user_id",
                    "posts.topic_id",
                    "posts.created_at",
                    "posts.updated_at",
                    "posts.is_active",
                    "users.username",
                    "users.profile_picture",
                ])
                ->where("posts.topic_id", $topicId)
                ->where("posts.is_active", "=", 1)
                ->orderBy("posts.created_at", "ASC")
                ->limit($perPage)
                ->offset($offset);
            $postsCache = new AdvancedDBCache(
                $postsDB,
                60,
                "topic_posts:$topicId:$page:$perPage"
            );
            $posts = $postsCache->get();

            foreach ($posts as &$post) {
                $post["like_count"] = PostsController::getLikeCount(
                    $post["id"]
                );
                $post["content"] = Crypto::decrypt($post["content"]);
                if (empty($post["profile_picture"])) {
                    $post["profile_picture"] =
                        "/assets/images/default-avatar.png";
                }
            }
            if($array){
                return [
                    "posts" => $posts,
                    "total" => $total,
                    "page" => $page,
                    "per_page" => $perPage,
                ];
            } else {
                echo JsonKit::json(
                    [
                        "posts" => $posts,
                        "total" => $total,
                        "page" => $page,
                        "per_page" => $perPage,
                    ],
                    "Konuya Ait Yanıtlar"
                );
            }
        } catch (\Throwable $th) {
            echo JsonKit::fail("Bir Hata Oluştu : " . $th->getMessage());
        }
    }

    public static function getRelatedTopics($topicId, $limit = 5, $array = false)
    {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=marlexforum", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT tag_id FROM topic_tags WHERE topic_id = ?");
            $stmt->execute([$topicId]);
            $tags = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($tags)) {
                if($array){
                    return $tags;
                } else {
                    return JsonKit::json([], "Hiç tag bulunamadı");
                }
            }

            $placeholders = implode(",", array_fill(0, count($tags), "?"));

            $sql = "SELECT DISTINCT t.id, t.title, t.description, t.slug, t.user_id, t.category_id, t.created_at
                    FROM topics t
                    INNER JOIN topic_tags tt ON t.id = tt.topic_id
                    WHERE tt.tag_id IN ($placeholders)
                      AND t.id != ?
                    LIMIT $limit";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_merge($tags, [$topicId]));
            $related = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($related as &$topic) {
                $topic['post_count'] = PostsController::getPostsCountByTopicId($topic['id']);
            }
            if($array){
                return $related;
            } else {
                return JsonKit::json($related, "Benzer Konular");
            }

        } catch (\PDOException $e) {
            if($array){
                return false;
            } else {
                return JsonKit::fail("Bir hata meydana geldi: " . $e->getMessage());
            }
        }
    }
}
