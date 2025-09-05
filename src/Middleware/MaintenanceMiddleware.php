<?php
namespace App\Middleware;

use App\Controllers\PagesController;
use App\Models\AuthSystem;
use App\Controllers\SiteController;
use App\Controllers\SettingsController;

class MaintenanceMiddleware
{
    private $settings;
    private $pages;
    private $auth;

    public function __construct(SettingsController $settings, PagesController $pages, AuthSystem $auth)
    {
        $this->settings = $settings;
        $this->pages = $pages;
        $this->auth = $auth;
    }

    public function handle(): void
    {
        $maintence = $this->settings->getSetting('maintenceMode');
        $requireCaptcha = $this->settings->getSetting('requireCaptchaRegistiration');

        if ($maintence !== "true") return;

        if (SiteController::checkWhitelist(SiteController::getIp()) === false) {
            if ($requireCaptcha === "true" && strpos($_SERVER['REQUEST_URI'], '/api') !== 0) {
                $this->handleCaptcha();
            } else {
                $this->pages->maintence();
                exit();
            }
        }
    }

    private function handleCaptcha(): void
    {
        if (!empty($_COOKIE['captchaToken'])) {
            $this->auth->checkToken($_COOKIE['captchaToken']);
            $this->pages->maintence();
            exit();
        }

        if ($_SERVER['REQUEST_URI'] !== '/verifyCaptcha') {
            AuthSystem::includeVerifyCaptchaPage();
            exit();
        }
    }
}
