<?php

namespace App\Services;

use App\Models\Announce;

class AnnouncementService
{
    private Announce $announce;

    public function __construct()
    {
        $this->announce = new Announce();
    }

    public function addAnnouncement(array $data): bool
    {
        return $this->announce->addAnnouncement([
            "title" => htmlspecialchars($data["title"]),
            "content" => htmlspecialchars($data["message"]),
            "expires_at" => htmlspecialchars($data["dtInput"]),
        ]);
    }

    public function checkAnnounceDate(int $announceId): void
    {
        $this->announce->checkDate($announceId);
    }

    public function getActiveAnnounces(int $limit = 5): array
    {
        return $this->announce->getAnnouncesActive($limit);
    }
}
