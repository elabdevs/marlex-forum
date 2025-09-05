<?php

namespace App\Controllers;

use App\Controllers\CategoryController;
use App\Models\Crypto;
use App\Models\DB;
use App\Core\Router;
use App\Models\AdvancedDBCache;
use App\Models\Setting;
use App\Repositories\CategoryRepository;

class PagesController
{
    private $siteInfo;
    private $topics;
    private $categories;
    private $activeAdmins;
    private $activeUsers;
    private $notifications;
    private $chatController;
    private $settings;
    private $siteVariables;
    private $category;
    public function __construct()
    {
        $this->siteInfo = SiteController::getSiteInfo();
        $this->topics = TopicsController::getTopics(3);
        $this->chatController = new ChatController();
        $this->category = new CategoryController(new CategoryRepository());
        $this->categories = $this->category->getCategories(3);
        $this->activeAdmins = UsersController::getOnlineAdmins();
        $this->activeUsers = UsersController::getOnlineUsers();
        $this->notifications = NotificationsController::getNotificationsByUserId(
            @$_SESSION["user_id"]
        );
        $this->settings = new SettingsController(new Setting());
        $this->siteVariables = SiteController::getSiteVariables();
        foreach ($this->topics as &$topic) {
            $topic["created_at"] = SiteController::timeAgo(
                $topic["created_at"]
            );
        }
    }
    public function __call($method, $args)
    {
        $this->renderPage($method);
    }
    private function loadPage(string $pageName, array $pageData = [])
    {
        ActivityController::saveUserActivity();
        extract(
            array_merge(
                [
                    "siteTitle" => $this->siteInfo["siteTitle"],
                    "topics" => $this->topics,
                    "categories" => $this->categories,
                    "activeAdmins" => $this->activeAdmins,
                    "activeUsers" => $this->activeUsers,
                    "notifications" => $this->notifications,
                    "siteVariables" => $this->siteVariables,
                    "notificationCount" => count($this->notifications),
                    "messages" => $this->chatController->getMessagesFromDB(3),
                    "header" => "./src/Views/Partials/header.php",
                    "jumbotron" => file_get_contents(
                        "./src/Views/Partials/jumbotron.php"
                    ),
                    "footer" => file_get_contents(
                        "./src/Views/Partials/footer.php"
                    ),
                ],
                $pageData
            )
        );
        $filePath = "./src/Views/Pages/{$pageName}.php";
        if (!file_exists($filePath)) {
            http_response_code(404);
            echo "404 Sayfa bulunamadı!";
            return;
        }
        require_once $filePath;
    }
    public function renderPage(string $pageName)
    {
        $pageControllerClass =
            "\\App\\Controllers\\" . ucfirst($pageName) . "Controller";
        $pageData = [];
        if (class_exists($pageControllerClass)) {
            $controller = new $pageControllerClass();
            if (method_exists($controller, "getData")) {
                $pageData = $controller->getData();
            }
        }
        $this->loadPage($pageName, $pageData);
    }
    public function dashboard()
    {
        ActivityController::saveUserActivity();
        $siteTitle = SiteController::getSiteVariable("siteTitle");
        $metaData = (new SEOController())->getMetaDataBySlug("dashboard");
        $head = "./src/Views/Partials/head-default.php";
        $header = "./src/Views/Partials/header.php";
        $this->loadPage("dashboard", [
            "siteTitle" => $siteTitle,
            "metaData" => $metaData,
            "head" => $head,
            "header" => $header,
        ]);
    }
    public function categories()
    {
        $categories = TopicsController::getTopicViewCounts();
        $siteTitle = SiteController::getSiteVariable("siteTitle");
        $metaData = (new SEOController())->getMetaDataBySlug("categories");
        $head = "./src/Views/Partials/head-default.php";
        $header = "./src/Views/Partials/header.php";
        $this->loadPage("categories", [
            "siteTitle" => $siteTitle,
            "metaData" => $metaData,
            "head" => $head,
            "header" => $header,
            "categories" => $categories,
        ]);
    }

    public function categoryTopics($slug)
    {
        $siteTitle = SiteController::getSiteVariable("siteTitle");
        $metaData = (new SEOController())->getMetaDataBySlug("categories");
        $head = "./src/Views/Partials/head-default.php";
        $header = "./src/Views/Partials/header.php";

        $categoryName = $this->category->getCategoryInfoBySlug($slug)[
            "name"
        ];
        $this->loadPage("categoryTopics", [
            "siteTitle" => $siteTitle,
            "metaData" => $metaData,
            "head" => $head,
            "header" => $header,
            "categoryName" => $categoryName,
        ]);
    }
    public function topic($slug)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $siteTitle = SiteController::getSiteVariable("siteTitle");
        $metaData = (new SEOController())->getMetaDataBySlug("topics");
        $settings = $this->settings->getSetting("viewTopicIsLoggedCheck");

        if ($settings == "true" && !@$_SESSION["user_id"]) {
            SiteController::sessionError();
            return;
        }

        $userId = $_SESSION["user_id"] ?? null;
        $userInfo = UsersController::getUserInfo($userId);
        $siteTitle = $this->siteInfo["siteTitle"];
        $notifications = $this->notifications;
        $notificationCount = count($notifications);

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
                "topics.meta_title",
                "topics.meta_description",
                "topics.created_at",
                "topics.updated_at",
                "topics.is_active",
                "users.username",
            ])
            ->where("topics.slug", $slug);

        $topicCache = new AdvancedDBCache($topicDB, 300, "topic:$slug");
        $topic = $topicCache->first();
        if (!$topic) {
            Router::notFound();
            return;
        }

        $userRoles = [];
        $roles = DB::table("user_roles")
            ->join(
                "user_role_assignments",
                "id",
                "=",
                "user_role_assignments.role_id"
            )
            ->where("user_role_assignments.user_id", $topic["user_id"])
            ->get();
        if ($roles) {
            foreach ($roles as $key => $role) {
                $roles[$key] = $role["role_name"];
            }
        }

        $topic["user_roles"] = [
            "data" => $roles,
        ];

        $categoryInfo = $this->category->getCategoryInfoByTopicId(
            $topic["id"]
        );

        if (@!$_SESSION["is_admin"] && $topic["is_active"] == 0) {
            Router::notFound();
            return;
        }

        $metaTitle = $topic["meta_title"] ?: $metaData["title"];
        $metaDescription =
            $topic["meta_description"] ?: $metaData["description"];
        $topicId = $topic["id"];
        $checkRemoved = TopicsController::checkRemoved($topicId);
        if ($checkRemoved && @!$_SESSION["is_admin"]) {
            Router::notFound();
            return;
        }

        $livePresenceSave = SiteController::saveLivePresence($topicId);
        $topicContent = Crypto::decrypt($topic["content"]);

        $likeCount = (new AdvancedDBCache(
            DB::table("likes")->where("post_id", $topicId),
            30,
            "like_count:$topicId"
        ))->count();

        $favoriteCount = (new AdvancedDBCache(
            DB::table("favorites")->where("topic_id", $topicId),
            30,
            "favorite_count:$topicId"
        ))->count();

        if ($settings == true) {
            $checkLiked = TopicsController::checkLiked($topicId, $userId);
            $checkFavorited = TopicsController::checkFavorited(
                $topicId,
                $userId
            );
            $postsCountByUser = PostsController::getPostsCountByUserId($userId);
            $topicsCountByUser = TopicsController::getTopicsCountByUserId(
                $userId
            );
        }

        $tags = TopicsController::getAllTagsJson($topicId);

        $commentsDB = DB::table("posts")
            ->join("users", "user_id", "=", "users.id")
            ->select([
                "posts.id",
                "posts.topic_id",
                "posts.user_id",
                "posts.content",
                "posts.created_at",
                "posts.updated_at",
                "posts.is_active",
                "users.username",
            ])
            ->where("posts.topic_id", $topicId)
            ->where("posts.is_active", 1);

        $commentsCache = new AdvancedDBCache(
            $commentsDB,
            300,
            "topic_comments:$topicId"
        );
        $comments = $commentsCache->get();

        if ($settings == true) {
            $viewed = DB::table("user_views")
                ->where("user_id", $userId)
                ->where("topic_id", $topicId)
                ->first();

            if (!$viewed) {
                DB::table("topics")
                    ->where("id", $topicId)
                    ->update(["views" => $topic["views"] + 1]);

                DB::table("user_views")->insert([
                    "user_id" => $userId,
                    "topic_id" => $topicId,
                    "viewed_at" => date("Y-m-d H:i:s"),
                ]);
            }
        }

        $head = "./src/Views/Partials/head-topic.php";
        $header = "./src/Views/Partials/header.php";

        $this->loadPage("viewTopic", [
            "settings" => $settings,
            "userId" => $userId,
            "userInfo" => $userInfo,
            "categoryInfo" => $categoryInfo,
            "siteTitle" => $siteTitle,
            "notifications" => $notifications,
            "notificationCount" => $notificationCount,
            "topic" => $topic,
            "topicId" => $topicId,
            "topicContent" => $topicContent,
            "likeCount" => $likeCount,
            "favoriteCount" => $favoriteCount,
            "checkLiked" => $checkLiked ?? null,
            "checkRemoved" => $checkRemoved ?? null,
            "checkFavorited" => $checkFavorited ?? null,
            "postsCountByUser" => $postsCountByUser ?? 0,
            "topicsCountByUser" => $topicsCountByUser ?? 0,
            "tags" => $tags,
            "metaTitle" => $metaTitle,
            "metaDescription" => $metaDescription,
            "postsCount" => $postsCountByUser ?? 0,
            "comments" => $comments,
            "header" => $header,
            "head" => $head,
            "metaData" => $metaData,
        ]);
    }

    public function chat()
    {
        UsersController::checkAuth();
        $this->loadPage("chat");
    }

    public function createTopic()
    {
        UsersController::checkAuth();
        $header = file_get_contents("./src/Views/Partials/header.php");
        $categories = TopicsController::getTopicViewCounts();
        $siteTitle = $this->siteInfo["siteTitle"];
        $maxPostLength = $this->settings->getSetting("maxPostLength");
        return include "./src/Views/Pages/createTopic.php";
    }
    public function profile()
    {
        $own = true;
        UsersController::checkAuth();
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        $userInfo = UsersController::getUserInfo($_SESSION["user_id"]);
        $bio = $userInfo["bio"] ?: "Biyografi yazılmamış.";
        $userLogs = ActivityController::getUserLogs($_SESSION["user_id"]);
        $userRoles = UsersController::getUserRolesArray($_SESSION["user_id"]);
        $highestRole = UsersController::getRoleNameByRoleId(
            UsersController::getHighestRoleId($_SESSION["user_id"])
        );
        $joinedAt = SiteController::timeAgo($userInfo["created_at"]);
        $postsCount = PostsController::getPostsCountByUserId(
            $_SESSION["user_id"]
        );
        $profileViews = UsersController::getProfileViews($_SESSION["user_id"]);
        $this->loadPage("profile", [
            "own" => $own,
            "userInfo" => $userInfo,
            "bio" => $bio,
            "userLogs" => $userLogs,
            "userRoles" => $userRoles,
            "highestRole" => $highestRole,
            "joinedAt" => $joinedAt,
            "postsCount" => $postsCount,
            "profileViews" => $profileViews,
        ]);
    }

    public function user($username)
    {
        $own = false;
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        $userInfo = UsersController::getUserInfoByUsername($username);
        $bio = $userInfo["bio"] ?: "Biyografi yazılmamış.";
        $userLogs = ActivityController::getUserLogs($userInfo["id"]);
        $userRoles = UsersController::getUserRolesArray($userInfo["id"]);
        $highestRole = UsersController::getRoleNameByRoleId(
            UsersController::getHighestRoleId($userInfo["id"])
        );
        $joinedAt = SiteController::timeAgo($userInfo["created_at"]);
        $postsCount = PostsController::getPostsCountByUserId($userInfo["id"]);
        $profileViews = UsersController::getProfileViews($userInfo["id"]);
        $this->loadPage("profile", [
            "own" => $own,
            "userInfo" => $userInfo,
            "bio" => $bio,
            "userLogs" => $userLogs,
            "userRoles" => $userRoles,
            "highestRole" => $highestRole,
            "joinedAt" => $joinedAt,
            "postsCount" => $postsCount,
            "profileViews" => $profileViews,
        ]);
    }
    public function login()
    {
        if (
            @$_SESSION["user_id"] &&
            UsersController::checkUserId(@$_SESSION["user_id"]) ===
                @$_SESSION["user_id"]
        ) {
            header("Location: /");
            exit();
        }
        $this->loadPage("login");
    }
    public function register()
    {
        if (
            @$_SESSION["user_id"] &&
            UsersController::checkUserId(@$_SESSION["user_id"]) ===
                @$_SESSION["user_id"]
        ) {
            header("Location: /");
            exit();
        }
        $this->loadPage("register");
    }
    public function reports()
    {
        UsersController::checkAdmin();
        $reportedPosts = PostsController::getReports();
        $this->loadPage("reports", ["reportedPosts" => $reportedPosts]);
    }
    public function userLogs()
    {
        UsersController::checkAdmin();
        $userLogs = ActivityController::getUserLogs();
        $this->loadPage("userLogs", ["userLogs" => $userLogs]);
    }
    public function userBans()
    {
        UsersController::checkAdmin();
        $bannedUsers = UsersController::getUserBans();
        $this->loadPage("userBans", ["bannedUsers" => $bannedUsers]);
    }
    public function adminDashboard()
    {
        $this->loadPage("admin/dashboard", [
            "totalUsers" => UsersController::getTotalUsersCount(),
            "totalCategories" => $this->category->getTotalCategoryCount(),
            "totalTopics" => TopicsController::getTotalTopicCount(),
        ]);
    }
    public function users()
    {
        $this->loadPage("admin/users", [
            "users" => UsersController::getAllUsers(),
            "roles" => UsersController::getAllRoles(),
        ]);
    }
    public function admin()
    {
        $settings = (new AdminController())->getSettings();
        $this->loadPage("admin", [
            "role" => UsersController::getRoleNameByRoleId(
                UsersController::getHighestRoleId($_SESSION["user_id"])
            ),
            "userInfo" => UsersController::getUserInfo($_SESSION["user_id"]),
            "users" => UsersController::getAllUsers(),
            "roles" => UsersController::getAllUserRoles(),
            "settings" => $settings,
        ]);
    }

    public function maintence()
    {
        $this->loadPage("maintence");
    }
}
