<?php

namespace App\Controllers;

use App\Models\DB;
use App\Models\Crypto;

class PostsController
{
    public function sendPost()
    {
        try {
            UsersController::checkAuth();
            $input = json_decode(file_get_contents("php://input"), true);
            $postContent = Crypto::encrypt($input["content"]);
            $topic_id = DB::filter($input["topicId"]);
            if ($postContent) {
                $sql = DB::table("posts")->insert([
                    "user_id" => DB::filter($_SESSION["user_id"]),
                    "topic_id" => $topic_id,
                    "content" => $postContent,
                ]);
                if ($sql) {
                    JsonKit::success("Yanıt Başarıyla Gönderildi");
                } else {
                    JsonKit::fail("Bir Hata Oluştu");
                }
            }
        } catch (\Throwable $th) {
            JsonKit::fail("Bir Hata Oluştu : " . $th->getMessage());
        }
    }

    public static function reportPost()
    {
        try {
            UsersController::checkAuth();
            $post_id = DB::filter($_POST["post_id"]);
            $topic_id = DB::filter($_POST["topic_id"]);
            $checkReport = DB::table("reports")
                ->where("topic_id", $topic_id)
                ->where("reported_by", $_SESSION["user_id"])
                ->where("post_id", $post_id)
                ->first();
            if (!$checkReport) {
                if ($post_id) {
                    $sql = DB::table("reports")->insert([
                        "topic_id" => $topic_id,
                        "reported_by" => DB::filter($_SESSION["user_id"]),
                        "post_id" => $post_id,
                    ]);
                    if ($sql) {
                        JsonKit::success("Post Raporlanıldı");
                    } else {
                        JsonKit::fail("Bir Hata Oluştu");
                    }
                }
            } else {
                JsonKit::failWithoutHRC("Zaten Bu Postu Raporladınız.");
            }
        } catch (\Throwable $th) {
            JsonKit::fail("Bir Hata Oluştu : " . $th->getMessage());
        }
    }

    public static function deletePost()
    {
        try {
            UsersController::checkAuth();
            $post_id = DB::filter($_POST["post_id"]);
            $topic_id = DB::filter($_POST["topic_id"]);
            if ($post_id) {
                $sql = DB::table("posts")
                    ->where("id", $post_id)
                    ->where("topic_id", $topic_id)
                    ->where("user_id", $_SESSION["user_id"])
                    ->update([
                        "is_active" => 0,
                    ]);
                if ($sql) {
                    JsonKit::success("Post Silindi");
                } else {
                    JsonKit::fail("Bir Hata Oluştu");
                }
            }
        } catch (\Throwable $th) {
            JsonKit::fail("Bir Hata Oluştu : " . $th->getMessage());
        }
    }

    public static function getReports()
    {
        try {
            UsersController::checkAdmin();
            $reports = DB::table("reports")->get();
            return $reports;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public static function getReportById($id)
    {
        try {
            UsersController::checkAdmin();
            return DB::table("reports")
                ->where("id", $id)
                ->first();
        } catch (\Throwable $th) {
            JsonKit::fail("Bir Hata Oluştu : ", $th->getMessage());
        }
    }

    public static function getPostsCountByUserId($userId)
    {
        try {
            $count = DB::table("posts")
                ->where("user_id", $userId)
                ->count();
            if ($count > 0) {
                return $count;
            } else {
                return 0;
            }
        } catch (\Throwable $th) {
            JsonKit::fail("Bir Hata Oluştu : ", $th->getMessage());
        }
    }

    public static function getPostsCountByTopicId($topicId)
    {
        try {
            $count = DB::table("posts")
                ->where("topic_id", $topicId)
                ->count();
            if ($count > 0) {
                return $count;
            } else {
                return 0;
            }
        } catch (\Throwable $th) {
            JsonKit::fail("Bir Hata Oluştu : ", $th->getMessage());
        }
    }

    public static function likePost($data = null)
    {
        try {
            if(session_status() == PHP_SESSION_NONE){
                session_start();
            }
            $data = json_decode($data, true);
            if($data){
                $post_id = DB::filter($data["post_id"]);
                $userId = DB::filter($data["user_id"]);
            } else {
                $post_id = DB::filter(@$_POST["post_id"]);
                $userId = DB::filter(@$_SESSION["user_id"]);
            }
            if (empty($userId)) {
                echo JsonKit::failWithoutHRC(
                    "Giriş yapmadan konu beğenilemez."
                );
                exit();
            }

            $checkLiked = DB::table("likes")
                ->where("user_id", $userId)
                ->where("reply_id", $post_id)
                ->where("is_active", 1);

            if ($checkLiked->first()) {
                $checkLiked->update([
                    "is_active" => 0,
                ]);
                if($data){
                    return [
                        "like_count" => self::getLikeCount($post_id),
                    ];
                } else {
                    echo JsonKit::json(
                        [
                            "like_count" => self::getLikeCount($post_id),
                        ],
                        "Yanıt Beğenisi Geri Çekildi"
                    );
                    exit();
                }
            }

            $postInfo = self::getPostInfo($post_id);
            $likedUserInfo = UsersController::getUserInfo($userId);

            $likedUsername = $likedUserInfo["username"];
            $postTitle = $postInfo["id"];
            $postOwner = $postInfo["user_id"];

            $sql = DB::table("likes")
                ->where("user_id", $userId)
                ->where("reply_id", $post_id)
                ->where("is_active", 0);
            date_default_timezone_set("Europe/Istanbul");
            if ($sql->first()) {
                $sql->update([
                    "is_active" => 1,
                    "liked_at" => date("Y-m-d H:i:s"),
                ]);
                if($data){
                    return [
                        "like_count" => self::getLikeCount($post_id),
                    ];
                } else {
                    echo JsonKit::json(
                        [
                            "like_count" => self::getLikeCount($post_id),
                        ],
                        "Yanıt Beğenildi"
                    );
                    $notificationData = [
                        "user_id" => $postOwner,
                        "type" => "like",
                        "message" =>
                            $likedUsername .
                            " Adlı Kullanıcı, " .
                            $postTitle .
                            " Kimlik Numaralı Yanıtınızı Beğendi.",
                    ];
                    NotificationsController::addNotification($notificationData);
                }
            } else {
                $sql = DB::table("likes")->insert([
                    "user_id" => $userId,
                    "reply_id" => $post_id,
                    "is_active" => 1,
                ]);
                UsersController::updateUserPoints($userId, 1);
                if($data){
                    return [
                        "like_count" => self::getLikeCount($post_id),
                    ];
                } else {
                    echo JsonKit::json(
                        [
                            "like_count" => self::getLikeCount($post_id),
                        ],
                        "Yanıt Beğenildi"
                    );
                    $notificationData = [
                        "user_id" => $postOwner,
                        "type" => "like",
                        "message" =>
                            $likedUsername .
                            " Adlı Kullanıcı, " .
                            $postTitle .
                            " Kimlik Numaralı Yanıtınızı Beğendi.",
                    ];
                    NotificationsController::addNotification($notificationData);
                }
            }
        } catch (\Throwable $th) {
            if($data){
                return false;
            } else {
                echo JsonKit::fail("Bir Hata Oluştu : " . $th->getMessage());
            }
        }
    }

    public static function getPostInfo($post_id)
    {
        try {
            return DB::table("posts")
                ->where("id", $post_id)
                ->first();
        } catch (\Throwable $th) {
            JsonKit::fail("Bir Hata Oluştu : ", $th->getMessage());
        }
    }

    public static function getLikeCount($post_id)
    {
        try {
            return DB::table("likes")
                ->where("reply_id", $post_id)
                ->where("is_active", 1)
                ->count();
        } catch (\Throwable $th) {
            JsonKit::fail("Bir Hata Oluştu : ", $th->getMessage());
        }
    }

    public static function checkPostLiked($post_id)
    {
        try {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $user_id = $_SESSION["user_id"];
            $checkLiked = DB::table("likes")
                ->where("user_id", $user_id)
                ->where("reply_id", $post_id)
                ->where("is_active", 1)
                ->first();
            if ($checkLiked) {
                echo JsonKit::success("Beğenildi");
            } else {
                echo JsonKit::failWithoutHRC("Beğenilmedi");
            }
        } catch (\Throwable $th) {
            JsonKit::fail("Bir Hata Oluştu : ", $th->getMessage());
        }
    }
}
