<?php

namespace App\Controllers;

use App\Models\Setting;

class SettingsController
{
    private Setting $settings;

    public function __construct(Setting $setting)
    {
        $this->settings = $setting;
    }

    public function getSetting(string $key): mixed
    {
        return $this->settings->get($key);
    }
}
