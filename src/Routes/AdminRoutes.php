<?php
namespace App\Routes;

use App\Controllers\AdminController;
use App\Controllers\AnnouncementController;
use App\Controllers\BackupController;
use App\Controllers\RestoreBackup;
use App\Controllers\PagesController;
use App\Controllers\UsersController;

class AdminRoutes
{
    public static function register(\App\Core\Router $router): void
    {
        $router->get('/admin', [PagesController::class, 'admin']);

        $router->group('/api/admin', function($router) {
            $router->get('/getDashboardData', [AdminController::class, 'getDashboardData']);
            $router->get('/getUsers', [AdminController::class, 'getUsers']);
            $router->get('/getReports', [AdminController::class, 'getReports']);
            $router->get('/getSystemInfo', [AdminController::class, 'getSystemInfo']);
            $router->post('/addAnnouncement', [AnnouncementController::class, 'addAnnounce']);
            $router->get('/getSettings', [AdminController::class, 'getSettings']);
            $router->post('/saveSiteSettings', [AdminController::class, 'saveAdminSettings']);
            $router->get('/backup/{type}', [BackupController::class, 'runBackup']);
            $router->post('/restoreBackup', [RestoreBackup::class, 'restoreBackup']);
        });
    }
}
