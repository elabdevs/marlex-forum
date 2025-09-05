<?php

namespace Views;

class AdminView {
    public static function renderDashboard($siteTitle, $dashboardData) {
        $navbar = file_get_contents("./src/Views/Partials/navbar.php");
        $jumbotron = file_get_contents("./src/Views/Partials/jumbotron.php");
        $footer = file_get_contents("./src/Views/Partials/footer.php");

        $totalUsers = $dashboardData['totalUsers'];
        $totalCategories = $dashboardData['totalCategories'];
        $totalTopics = $dashboardData['totalTopics'];
        $topics = $dashboardData['topics'];
        $categories = $dashboardData['categories'];

        include("./src/Views/Pages/admin/dashboard.php");
    }

    public static function renderUsers($siteTitle, $usersData) {
        $navbar = file_get_contents("./src/Views/Partials/navbar.php");
        $footer = file_get_contents("./src/Views/Partials/footer.php");

        $users = $usersData['users'];
        $roles = $usersData['roles'];

        include("./src/Views/Pages/admin/users.php");
    }
}
