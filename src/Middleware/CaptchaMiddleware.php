<?php
namespace App\Middleware;

use App\Controllers\PagesController;
use App\Models\AuthSystem;
use App\Controllers\SiteController;
use App\Controllers\SettingsController;

class CaptchaMiddleware
{
    private SettingsController $settings;
    private PagesController $pages;
    private AuthSystem $auth;

    public function __construct(SettingsController $settings, PagesController $pages, AuthSystem $auth)
    {
        $this->settings = $settings;
        $this->pages = $pages;
        $this->auth = $auth;
    }

    public function handle(): void
    {
        $requireCaptcha = $this->settings->getSetting('requireCaptchaRegistiration');

        if ($requireCaptcha !== "true") return;

        if (strpos($_SERVER['REQUEST_URI'], '/api') === 0) return;

        $this->checkCaptcha();
    }

    private function checkCaptcha(): void
    {
        if (!empty($_COOKIE['captchaToken'])) {
            $this->auth->checkToken($_COOKIE['captchaToken']);
            return;
        }

        if ($_SERVER['REQUEST_URI'] !== '/verifyCaptcha') {
            AuthSystem::includeVerifyCaptchaPage();
            exit();
        }
    }
}
