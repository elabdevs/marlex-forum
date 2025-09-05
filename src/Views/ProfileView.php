<?php

namespace Views;

use Controllers\SiteController;

class ProfileView {
    public static function render($userInfo, $siteInfo, $userLogs) {
        $navbar = SiteController::includePartials("navbar");
        $footer = SiteController::includePartials("footer");

        include("./src/Views/Pages/profile.php");
    }
}
