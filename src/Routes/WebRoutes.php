<?php
namespace App\Routes;

use App\Controllers\PagesController;
use App\Controllers\UsersController;
use App\Controllers\SiteController;

class WebRoutes
{
    public static function register(\App\Core\Router $router): void
    {
        $router->get('/', [PagesController::class, 'dashboard']);
        $router->get('/login', [PagesController::class, 'login']);
        $router->get('/register', [PagesController::class, 'register']);
        $router->get('/profile', [PagesController::class, 'profile']);
        $router->get('/chat', [PagesController::class, 'chat']);
        $router->get('/logout', [UsersController::class, 'logout']);
        $router->get('/verifyCaptcha', [SiteController::class, 'renderCaptchaPage']);
        $router->post('/verifyCaptcha', [SiteController::class, 'verifyCaptcha']);
        $router->get('/create-topic', [PagesController::class, 'createTopic']);
        $router->get("/categories", [PagesController::class, 'categories']);
        $router->get('/topics/{slug}', [PagesController::class, 'topic']);
        $router->get('/categories/{slug}', [PagesController::class, 'categoryTopics']);
        $router->get('/user/{username}', [PagesController::class, 'user']);
        $router->get('/admin', [PagesController::class, 'admin']);
    }
}
