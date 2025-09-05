<?php

namespace App\Controllers;

use App\Models\DB;
use App\Controllers\SiteController;

class SEOController
{
    private string $table = "seo_settings";

    public function getMetaDataBySlug(string $slug): ?array
    {
        try {
            $seo = DB::table($this->table)
                ->where("page", $slug)
                ->first();

            if ($seo) {
                $seo = is_object($seo) ? (array) $seo : $seo;

                $siteController = new SiteController();
                $seo = $siteController->replaceVariablesInArray($seo);
            }

            return $seo ?: null;
        } catch (\Exception $e) {
            error_log("SEO fetch error: " . $e->getMessage());
            return null;
        }
    }

    private function getBaseUrl(): string
    {
        return (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on"
            ? "https"
            : "http") . "://$_SERVER[HTTP_HOST]";
    }
}
