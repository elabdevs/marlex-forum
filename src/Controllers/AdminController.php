<?php
namespace App\Controllers;

use App\Services\AdminService;
use App\Controllers\JsonKit;

class AdminController
{
    private AdminService $service;

    public function __construct()
    {
        $this->service = new AdminService();
    }

    public function getDashboardData(): void
    {
        $data = $this->service->getDashboardData();
        echo JsonKit::json($data,"Anasayfa verileri getirildi.",200);
    }

    public function getUsers(): void
    {
        $users = $this->service->getUsers();
        echo JsonKit::json($users,"Kullanıcılar getirildi.",200);
    }

    public function getReports(): void
    {
        $reports = $this->service->getReports();
        echo JsonKit::json($reports,"Raporlar getirildi.",200);
    }

    public function saveAdminSettings(): void
    {
        $input = file_get_contents("php://input");
        $data = json_decode($input,true);

        $save = $this->service->saveAdminSettings($data);

        if ($save) echo JsonKit::json($data,"Ayarlar kaydedildi.",200);
        else echo JsonKit::fail("Ayarlar kaydedilemedi.");
    }

    public function getSettings(): array
    {
        return $this->service->getAllSettings();
    }

    public function getSystemInfo(): void
    {
        $status = $this->service->getSystemInfo();
        echo JsonKit::json($status, "Sistem bilgileri getirildi.", 200);
    }
}
