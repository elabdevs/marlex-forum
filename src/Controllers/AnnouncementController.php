<?php

namespace App\Controllers;

use App\Services\AnnouncementService;
use App\Models\Setting;

class AnnouncementController
{
    private AnnouncementService $service;
    private SettingsController $setting;

    public function __construct()
    {
        $this->service = new AnnouncementService();
        $this->setting = new SettingsController(new Setting());
    }

    public function addAnnounce(): void
    {
        $reqData = file_get_contents("php://input");
        $data = json_decode($reqData, true);

        $create = $this->service->addAnnouncement($data);

        if ($create) {
            echo JsonKit::success("Duyuru Başarıyla Eklendi");
        } else {
            echo JsonKit::failWithoutHRC("Bir hata oluştu");
        }
    }

    public function checkAnnounceDate(int $announceId): void
    {
        $this->service->checkAnnounceDate($announceId);
    }

    public function getAnnounces(): void
    {
        $dashboardAnnounceLimit = $this->setting->getSetting("dashboardAnnounceLimit");
        $announces = $this->service->getActiveAnnounces($dashboardAnnounceLimit);

        echo JsonKit::json($announces, "Duyurular getirildi.", 200);
    }
}
