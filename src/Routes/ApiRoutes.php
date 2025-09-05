<?php
namespace App\Routes;

use App\Controllers\APIControllerV1;
use App\Controllers\UsersController;
use App\Controllers\RegisterController;
use App\Controllers\LoginController;
use App\Controllers\TopicsController;
use App\Controllers\PostsController;
use App\Controllers\ChatController;
use App\Controllers\AnnouncementController;
use App\Controllers\ActivityController;
use App\Controllers\ImageController;
use App\Controllers\AuthController;
use App\Controllers\SecurityController;

class ApiRoutes
{
    public static function register(\App\Core\Router $router): void
    {
        $router->group('/api', function($router) {
            $router->get('/user/{id}', [UsersController::class, 'getUserProfile']);
            $router->get('/generateCSRFToken', [SecurityController::class, 'generateCSRFToken']);
            $router->get('/verifyCSRF/{csrfToken}', [SecurityController::class, 'verifyCSRFToken']);
            $router->post('/registerUser', [RegisterController::class, 'registerUser']);
            $router->post('/loginUser', [LoginController::class, 'loginUser']);
            $router->post('/createTopic', [TopicsController::class, 'createTopic']);
            $router->post('/replyContent', [PostsController::class, 'sendPost']);
            $router->post('/reportPost', [PostsController::class, 'reportPost']);
            $router->post('/deletePost', [PostsController::class, 'deletePost']);
            $router->post('/likeTopic', [TopicsController::class, 'likeTopic']);
            $router->post('/likePost', [PostsController::class, 'likePost']);
            $router->get('/checkPostLiked/{id}', [PostsController::class, 'checkPostLiked']);
            $router->post('/favoriteTopic', [TopicsController::class, 'favoriteTopic']);
            $router->post('/sendMessage', [ChatController::class, 'sendDM']);
            $router->get('/getRelatedTopics/{id}', [TopicsController::class, 'getRelatedTopics']);
            $router->get('/getAnnounces', [AnnouncementController::class, 'getAnnounces']);
            $router->post('/archiveTopic', [TopicsController::class, 'archiveTopic']);
            $router->post('/removeTopic', [TopicsController::class, 'removeTopic']);
            $router->get('/getCategoryTopics/{slug}', [TopicsController::class, 'getTopicsByCategorySlug']);
            $router->get('/getTopicOnlines/{id}', [TopicsController::class, 'getTopicOnlines']);
            $router->get('/getPostsByTopic/{id}', [TopicsController::class, 'getPostsByTopic']);
            $router->get('/getUserRoles/{id}', [UsersController::class, 'getUserRoles']);
            $router->get('/getUserRoles', [UsersController::class, 'getAllUserRoles']);
            $router->get('/lastActiveUsers/{minutes}', [ActivityController::class, 'getActiveUsers']);
            $router->get('/saveAfk', [ActivityController::class, 'saveAfk']);
            $router->get('/removeAfk', [ActivityController::class, 'removeAfk']);
            $router->post('/uploadAvatar', [ImageController::class, 'uploadAvatar']);
            $router->get('/exportUserData', [UsersController::class, 'exportUserData']);
            $router->delete('/deleteAccount', [UsersController::class, 'requestDeleteAccount']);
            $router->get('/getMessages/{chatId}', [ChatController::class, 'getDM']);
            $router->get('/recentChats', [ChatController::class, 'getLastMessageUsers']);
            $router->post('/checkAuthToken', [AuthController::class, 'checkAuthToken']);
        });

        $router->group('/api/v1', function($router) {
            $router->post('/authorize', [APIControllerV1::class, 'authorize']);
            $router->get('/users', [APIControllerV1::class, 'getUsers']);
            $router->get('/user/{id}', [APIControllerV1::class, 'getUsers']);
            $router->get('/getUserRoles/{id}', [APIControllerV1::class, 'getUserRoles']);
            $router->get('/getUserRoles', [APIControllerV1::class, 'getUserRoles']);
            $router->get('/lastActiveUsers/{minutes}', [APIControllerV1::class, 'getActiveUsers']);
            $router->get('/topics', [APIControllerV1::class, 'getTopics']);
            $router->post('/createTopic', [APIControllerV1::class, 'createTopic']);
            $router->post('/removeTopic/{id}', [APIControllerV1::class, 'removeTopic']);
            $router->post('/likeTopic/{id}', [APIControllerV1::class, 'likeTopic']);
            $router->post('/favoriteTopic/{id}', [APIControllerV1::class, 'favoriteTopic']);
            $router->get('/getRelatedTopics/{id}', [APIControllerV1::class, 'getRelatedTopics']);
            $router->get('/getCategoryTopics/{id}', [APIControllerV1::class, 'getCategoryTopics']);
            $router->get('/getPostsByTopic/{id}', [APIControllerV1::class, 'getPostsByTopic']);
            $router->post('/replyContent/{id}', [APIControllerV1::class, 'replyContent']);
            $router->post('/reportPost', [APIControllerV1::class, 'reportPost']);
            $router->post('/likePost', [APIControllerV1::class, 'likePost']);
            $router->post('/sendMessage', [APIControllerV1::class, 'sendDM']);
            $router->get('/getMessages/{chatId}', [APIControllerV1::class, 'getDM']);
            $router->get('/recentChats', [APIControllerV1::class, 'getLastMessageUsers']);
        });
    }
}
