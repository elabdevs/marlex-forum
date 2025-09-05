<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\AuthorizationService;

class AuthController
{
    private AuthService $authService;
    private AuthorizationService $authorizationService;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->authorizationService = new AuthorizationService();
    }

    public function checkAuth(): void
    {
        if (!$this->authService->checkAuth()) {
            $this->authService->logout();
            die();
        }
    }

    public function checkAdmin(): void
    {
        if (!$this->authorizationService->checkAdmin()) {
            die();
        }
    }

    public function logout(): void
    {
        $this->authService->logout();
    }
}
