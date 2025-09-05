<?php

require './vendor/autoload.php';

use App\Core\Router;
use App\Middleware\MaintenanceMiddleware;
use App\Middleware\CaptchaMiddleware;
use App\Middleware\CorsMiddleware;
use App\Controllers\PagesController;
use App\Controllers\SettingsController;
use App\Models\AuthSystem;
use App\Routes\WebRoutes;
use App\Routes\ApiRoutes;
use App\Routes\AdminRoutes;
use App\Routes\InstallRoutes;
use App\Models\Setting;

date_default_timezone_set('Europe/Istanbul');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$settingModel = new Setting();
$settingsController = new SettingsController($settingModel);
$pagesController = new PagesController();
$authSystem = new AuthSystem();

$middlewareStack = [
    new CorsMiddleware(),
    new CaptchaMiddleware($settingsController, $pagesController, $authSystem),
    new MaintenanceMiddleware($settingsController, $pagesController, $authSystem)
];

foreach ($middlewareStack as $middleware) {
    $middleware->handle();
}

$router = new Router();

$routes = [
    WebRoutes::class,
    ApiRoutes::class,
    AdminRoutes::class,
    InstallRoutes::class
];

foreach ($routes as $routeClass) {
    $routeClass::register($router);
}


$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
