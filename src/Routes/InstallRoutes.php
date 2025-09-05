<?php
namespace App\Routes;

use App\Controllers\InstallationController;

class InstallRoutes
{
    public static function register(\App\Core\Router $router): void
    {
        $router->group('/install', function($router) {
            $router->get('/install', [InstallationController::class, 'handleRequest']);
            $router->post('/install', [InstallationController::class, 'handleRequest']);
        });
    }
}
